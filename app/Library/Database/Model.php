<?php

namespace App\Library\Database;


class Model implements ModelInterface
{
    protected $connection;
    protected $link;
    protected $table;
    protected $where = [];
    protected $select = ['*'];
    protected $orderBy;
    protected $limit;
    protected $app;

    public function __construct()
    {
        if ($this->connection == 'mysql') {
            $connection = new MySQLConnection();
        } else {
            $connection = new PgSQLConnection();
        }
        $this->link = $connection->getConnection();
    }
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        // TODO: Implement hasOne() method.
    }

    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        // TODO: Implement hasMany() method.
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

    public function where($column, $operator, $value)
    {
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

        $stmt = $this->link->prepare($query);
        $this->bindWhereValues($stmt);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $values = array_values($data);

        $query = "INSERT INTO " . $this->table . " (" . implode(", ", $columns) . ") VALUES (:" . implode(", :", $columns) . ")";

        $stmt = $this->link->prepare($query);
        $this->bindValues($stmt, $data);
        $stmt->execute();

        return $this->link->lastInsertId();
    }

    public function update(array $data)
    {
        $query = "UPDATE " . $this->table . " SET " . $this->buildUpdateClause($data);

        if (!empty($this->where)) {
            $query .= " WHERE " . $this->buildWhereClause();
        }

        $stmt = $this->link->prepare($query);
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

        $stmt = $this->link->prepare($query);
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
            $stmt->bindValue(":where$index", $condition[2]);
        }
    }

}
