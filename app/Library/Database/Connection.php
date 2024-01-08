<?php

declare(strict_types=1);

namespace App\Library\Database;

use App\Library\Application;
use App\Library\Support\Collection;
use PDO;

class Connection
{
    protected $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getPdo(): PDO
    {
        $factory = Application::getApplication()->make(ConnectionFactory::class);

        return $factory->getSingletonPdo($this->model->getConnection());
    }

    private function getOrderStr(): string
    {
        if (empty($this->model->getOrder())) {
            return '';
        }
        $str = 'order by';
        foreach ($this->model->getOrder() as $v) {
            $str .= $v[0].' '.$v[1];
        }
        return $str;
    }

    public function get()
    {
        $select = is_array($this->model->getColumns()) ? implode(", ",
            $this->model->getColumns()) : $this->model->getColumns();

        $query = 'select '.$select." from ".$this->model->getTableName();
        if (!empty($this->model->getWhere())) {
            $query .= ' where '.$this->buildWhereClause();
        }
        $query .= $this->getOrderStr();
        if (!is_null($this->model->getLimit())) {
            $query .= " limit ".$this->model->getLimit();
        }
        try {
            $stmt = $this->getPdo()->prepare($query);
            $this->bindWhereValues($stmt);
            $stmt->execute();
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->throwErr($stmt);
        } catch (\Throwable $exception) {
            throw $exception;
        }
        //with execute
        $withRelatedList = [];
        if (count($this->model->getWith())) {
            $localKeyArr = [];
            foreach ($this->model->getWith() as $k => $f) {
                if ($f instanceof \Closure) {
                    $class = $this->$k();
                    $f($class);
                    $name = $k;
                } else {
                    $name  = $f;
                    $class = $this->$f();
                }
                $withRelatedList[$name] = [
                    'class' => $class,
                ];
                $localKey               = $class->getLocalKey();
                $localKeyArr[$localKey] = [];
            }
            //批量提取，依赖值
            foreach ($list as $item) {
                foreach ($item as $key => $value) {
                    if (isset($localKeyArr[$key])) {
                        $localKeyArr[$key][] = $value;
                    }
                }
            }
            //数据库批量查询
            $withRelatedRecord = [];
            foreach ($withRelatedList as $fun => $call) {
                $key                     = $call['class']->getLocalKey();
                $withRelatedRecord[$fun] = $call['class']->setForeignKeyValue(array_unique($localKeyArr[$key]))->get();
            }
            //数据组装返回
            foreach ($list as $k => $item) {
                foreach ($withRelatedList as $fun => $call) {
                    $localKey   = $call['class']->getLocalKey();
                    $item[$fun] = $withRelatedRecord[$fun][$item[$localKey]] ?? [];
                }
                $list[$k] = $item;
            }
        }
        return new Collection($list);
    }


    public function keyByArray(array $list, string $key, bool $hasMany): array
    {
        $data = [];
        foreach ($list as $item) {
            if ($hasMany) {
                $data[$item[$key]][] = $item;
            } else {
                $data[$item[$key]] = $item;
            }
        }
        return $data;
    }

    public function first()
    {
        return new Collection($this->model->limit(1)->get()[0]);
    }

    public function find($id)
    {
        return new Collection($this->model->where('id', '=', $id)->first());
    }

    public function findOrFail($id)
    {
        $result = $this->find($id);
        if (!$result) {
            throw new \Exception("Model not found for id: $id");
        }
        return new Collection($result);
    }

    public function create(array $data)
    {
        $columns = array_keys($data);
        $query   = 'insert into '.$this->model->getTableName().' ('.implode(", ", $columns).') values (:'.implode(", :",
                $columns).")";
        $stmt    = $this->getPdo()->prepare($query);
        $this->bindValues($stmt, $data);
        $stmt->execute();
        $lastId = $this->getPdo()->lastInsertId();
        $this->throwErr($stmt);
        if ($lastId) {
            $data[$this->model->getPrimaryKey()] = $lastId;
            return new Collection($data);
        }
        return null;
    }

    /**
     * @param $stmt
     * @return void
     * @throws \Exception
     */
    public function throwErr($stmt):void{
        $info= $stmt->errorInfo();
        $stmt->closeCursor();
       if ($info){
           if ($info[0]!=='00000'){
               $message=implode(',',$info);
               throw  new \Exception($message);
           }
       }
    }

    public function update(array $data)
    {
        $query = 'update '.$this->model->getTableName()." set ".$this->buildUpdateClause($data);
        if (!empty($this->where)) {
            $query .= 'where '.$this->buildWhereClause();
        }
        $stmt = $this->getPdo()->prepare($query);
        $this->bindValues($stmt, $data);
        $this->bindWhereValues($stmt);
        $stmt->execute();
        $count= $stmt->rowCount();
        $this->throwErr($stmt);
        return $count;
    }

    public function delete(): int
    {
        $query = 'delete from '.$this->model->getTableName();
        if (!empty($this->where)) {
            throw  new \Exception("danger delete! no where");
        }
        $query .= " where ".$this->buildWhereClause();
        $stmt  = $this->getPdo()->prepare($query);
        $this->bindWhereValues($stmt);
        $stmt->execute();
        return $stmt->rowCount();
    }

    protected function buildUpdateClause($update)
    {
        $data = [];
        foreach (array_keys($update) as $field) {
            $data[] = "$field=:$field";
        }
        return implode(",", $data);
    }

    protected function bindValues($stmt, $data)
    {
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
    }

    protected function buildWhereClause()
    {
        $whereClause = '';
        foreach ($this->model->getWhere() as $index => $condition) {
            [$column, $operator, $value] = $condition;
            if ($index > 0) {
                $whereClause .= ' and ';
            }
            if ($operator == 'in') {
                // 为每个值创建一个占位符，例如：(:where0_0, :where0_1, ...)
                $w = '('.implode(',', array_map(function ($i) use ($index) {
                        return ":where{$index}_$i";
                    }, array_keys($value))).')';
            } else {
                $w = ":where$index";
            }
            $whereClause .= "$column $operator ".$w;
        }
        return $whereClause;
    }

    protected function bindWhereValues($stmt)
    {
        foreach ($this->model->getWhere() as $index => $condition) {
            if (is_array($condition[2])) {
                foreach ($condition[2] as $k => $val) {
                    $stmt->bindValue(":where{$index}_$k", $val);
                }
            } else {
                $stmt->bindValue(":where$index", $condition[2]);
            }
        }
    }


    public function beginTransaction(): bool
    {
        return $this->getPdo()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->getPdo()->commit();
    }

    public function rollBack(): bool
    {
        return $this->getPdo()->rollBack();
    }
}
