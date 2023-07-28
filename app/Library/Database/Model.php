<?php

namespace App\Library\Database;


use App\Library\Application;
use App\Library\Config\Config;
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
        $config = $this->app->make(Config::class);
        $driver = $config->config("app.db.connections.{$this->connection}driver");
        switch ($driver) {
            case 'mysql':
                $connection = new MySQLConnection($config->config('db.connections.' . $this->connection));
                break;
            case 'pgsql':
                $connection = new PgSQLConnection($config->config('db.connections.' . $this->connection));
                break;
            default:
                $connection = new MySQLConnection($config->config('db.connections.' . $this->connection));
        }
        $this->pdo = $connection->getConnection();
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
        }
        $this->with[] = $with;
        return $this;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns = ['*'])
    {
        $this->select = $columns;
        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        if ($value == null) {
            $value = $operator;
            $operator = '=';
        }
        $this->where[] = [$column, $operator, $value];
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = $column . ' ' . $direction;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function get()
    {
        $query = "SELECT " . implode(", ", $this->select) . " FROM " . $this->table;

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhereClause();
        }

        if (!is_null($this->orderBy)) {
            $query .= " ORDER BY " . $this->orderBy;
        }

        if (!is_null($this->limit)) {
            $query .= " LIMIT " . $this->limit;
        }

       echo $query.PHP_EOL;

        $stmt = $this->pdo->prepare($query);
        $this->bindWhereValues($stmt);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //with execute
        $withRelatedList = [];
        if (count($this->with)) {
            $localKeyArr = [];
            foreach ($this->with as $fun) {
                $class = $this->$fun();
                $withRelatedList[$fun] = [
                    'name' => get_class($class),
                    'class' => $class,
                ];
                $localKey = $class->getLocalKey();
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
                $key = $call['class']->getLocalKey();
                $withRelatedRecord[$fun] = $call['class']->setForeignKeyValue(array_unique($localKeyArr[$key]))->get();
            }
            //数据组装返回
            foreach ($list as $k => $item) {
                foreach ($withRelatedList as $fun => $call) {
                    $localKey = $call['class']->getLocalKey();
                    if ($call['class']['name'] == 'HasOne') {
                        $item[$fun] = isset($withRelatedRecord[$fun][$item[$localKey]]) ? array_pop($withRelatedRecord[$fun][$item[$localKey]]) : null;
                    } else {
                        $item[$fun] = isset($withRelatedRecord[$fun][$item[$localKey]]) ?? [];
                    }
                }
                $list[$k] = $item;
            }
        }
        return $list;
    }

    public function keyBy($key)
    {
        $this->keyBy = $key;
        return $this;
    }

    public function getKeyName()
    {
        return $this->keyBy;
    }

    public function keyByArray(array $list, string $key): array
    {
        $data = [];
        foreach ($list as $item) {
            $data[$item[$key]] = $item;
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
        $query = "INSERT INTO " . $this->table . " (" . implode(", ", $columns) . ") VALUES (:" . implode(", :", $columns) . ")";
        $stmt = $this->pdo->prepare($query);
        $this->bindValues($stmt, $data);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function update(array $data)
    {
        $query = "UPDATE " . $this->table . " SET " . $this->buildUpdateClause($data);
        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhereClause();
        }
        $stmt = $this->pdo->prepare($query);
        $this->bindValues($stmt, $data);
        $this->bindWhereValues($stmt);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table;
        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhereClause();
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
            $whereClause .= "$column $operator :where$index";
        }
        return $whereClause;
    }

    protected function bindWhereValues($stmt)
    {
        foreach ($this->where as $index => $condition) {
            if (is_array($condition[2])) {
                var_dump($condition[2]);
                $w = array_map(function ($a) {
                    return "'{$a}'";
                }, $condition[2]);
                $value = '(' . implode(',', $w) . ')';
                echo $value;
            } else {
                $value = $condition[2];
            }
            $stmt->bindValue(":where$index", $value);
        }
    }

}
