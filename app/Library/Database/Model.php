<?php

namespace App\Library\Database;


use App\Library\Application;
use PDO;

class Model implements ModelInterface
{
    protected $connection = 'mysql';
    protected $pdo;
    protected $table;
    protected $where = [];
    protected $select = ['*'];
    protected $orderBy;
    protected $limit;
    protected $app;
    protected $with = [];
    protected $primaryKey = 'id';
    protected $keyBy;

    public function __construct()
    {
        $this->app = Application::getApplication();
        $this->pdo = $this->app->make(ConnectionFactory::class)->getSingletonPdo($this->connection);
    }

    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        return new HasOne($related, $foreignKey, $localKey);
    }

    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        return new HasMany($related, $foreignKey, $localKey);
    }

    public function with($with)
    {
        if (is_array($with)) {
            $this->with = array_merge($this->with, $with);
        } else {
            $this->with[] = $with;
        }
        return $this;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select(...$columns)
    {
        if (empty($columns)) {
            $this->select = ['*'];
        } else {
            $this->select = $columns;
        }
        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        if ($value == null) {
            $value    = $operator;
            $operator = '=';
        }
        $this->where[] = [$column, $operator, $value];
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = $column.' '.$direction;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function get()
    {
        $select = is_array($this->select) ? implode(", ", $this->select) : $this->select;

        $query = "SELECT ".$select." FROM ".$this->table;

        if (!empty($this->where)) {
            $query .= " WHERE ".$this->buildWhereClause();
        }

        if (!is_null($this->orderBy)) {
            $query .= " ORDER BY ".$this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= " LIMIT ".$this->limit;
        }
        try {
            $stmt = $this->pdo->prepare($query);
            $this->bindWhereValues($stmt);
            $stmt->execute();
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $exception) {
            //  $stmt->debugDumpParams();
            throw $exception;
        }
        $keyBy = $this->getKeyName();
        if (is_array($keyBy)) {
            $list = $this->keyByArray($list, $keyBy[0], $keyBy[1]);
        }
        //with execute
        $withRelatedList = [];
        if (count($this->with)) {
            $localKeyArr = [];
            foreach ($this->with as $k => $f) {
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
        return $list;
    }

    public function keyBy($key, $hasMany = false)
    {
        $this->keyBy = [$key, $hasMany];
        return $this;
    }

    public function getKeyName()
    {
        return $this->keyBy;
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
        $results = $this->limit(1)->get();
        return count($results) > 0 ? $results[0] : null;
    }

    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
    }

    public function findOrFail($id)
    {
        $result = $this->find($id);
        if (!$result) {
            throw new \Exception("Model not found for id: $id");
        }
        return $result;
    }

    public function create(array $data)
    {
        $columns = array_keys($data);
        $query   = "INSERT INTO ".$this->table." (".implode(", ", $columns).") VALUES (:".implode(", :", $columns).")";
        $stmt    = $this->pdo->prepare($query);
        $this->bindValues($stmt, $data);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function update(array $data)
    {
        $query = "UPDATE ".$this->table." SET ".$this->buildUpdateClause($data);
        if (!empty($this->where)) {
            $query .= " WHERE ".$this->buildWhereClause();
        }
        $stmt = $this->pdo->prepare($query);
        $this->bindValues($stmt, $data);
        $this->bindWhereValues($stmt);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete()
    {
        $query = "DELETE FROM ".$this->table;
        if (!empty($this->where)) {
            $query .= " WHERE ".$this->buildWhereClause();
        }
        $stmt = $this->pdo->prepare($query);
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
        foreach ($this->where as $index => $condition) {
            [$column, $operator, $value] = $condition;
            if ($index > 0) {
                $whereClause .= ' AND ';
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
        foreach ($this->where as $index => $condition) {
            if (is_array($condition[2])) {
                foreach ($condition[2] as $k => $val) {
                    $stmt->bindValue(":where{$index}_$k", $val);
                }
            } else {
                $stmt->bindValue(":where$index", $condition[2]);
            }
        }
    }

    public function __call($name, ...$arguments)
    {
        $this->pdo->$name(...$arguments);
    }
}
