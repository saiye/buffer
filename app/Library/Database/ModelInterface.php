<?php

namespace App\Library\Database;

interface ModelInterface
{
    public function table($table);

    public function select($columns = ['*']);

    public function where($column, $operator, $value);

    public function orderBy($column, $direction = 'ASC');

    public function limit($limit);

    public function get();

    public function first();

    public function find($id);

    public function findOrFail($id);

    public function create(array $data);

    public function update(array $data);

    public function delete();

    public function hasOne($related, $foreignKey = null, $localKey = null);

    public function hasMany($related, $foreignKey = null, $localKey = null);
}
