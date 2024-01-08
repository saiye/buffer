<?php

namespace App\Library\Database;

interface ModelInterface
{
    public function table($table);

    public function select(...$columns);

    public function where($column, $operator, $value);

    public function orderBy($column, $direction = 'ASC');

    public function limit($limit);

    public function get();

    public function first();

    public function find($id);

    public function findOrFail($id);

    public function create(array $data);

    public function update(array $data);

    public function delete():int;

    public function hasOne($related, $foreignKey = null, $localKey = null);

    public function hasMany($related, $foreignKey = null, $localKey = null);

    public function with($with);

    public function beginTransaction():bool;

    public function commit():bool;

    public function rollBack():bool;

    public function getTableName():string;

    public function getConnection():string;

    public function getPrimaryKey():string;

    public function getColumns();

    public function getWhere():array;

    public function getOrder():array;

    public function getLimit():int;

    public function getWith():array;
}
