<?php

namespace App\Library\Database;

class HasOne  implements RelationInterface
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
        return $this->relatedModel->where($this->foreignKey, '=', $this->foreignKeyValue)->first();
    }

    public function setForeignKeyValue($value)
    {
        $this->foreignKeyValue = $value;
        return $this;
    }
}
