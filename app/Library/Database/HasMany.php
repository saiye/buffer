<?php

namespace App\Library\Database;

class HasMany implements RelationInterface
{
    protected $relatedModel;
    protected $foreignKey;
    protected $foreignKeyValue;

    public function __construct($relatedModel, $foreignKey)
    {
        $this->relatedModel = new $relatedModel(new MySQLConnection()); // 此处传入合适的数据库连接实例
        $this->foreignKey = $foreignKey;
    }

    public function get()
    {
        return $this->relatedModel->where($this->foreignKey, '=', $this->foreignKeyValue)->get();
    }

    public function setForeignKeyValue($value)
    {
        $this->foreignKeyValue = $value;
        return $this;
    }
}
