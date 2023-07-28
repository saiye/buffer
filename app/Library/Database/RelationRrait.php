<?php

namespace App\Library\Database;

trait RelationRrait
{

    public function setForeignKeyValue($value)
    {
        $this->foreignKeyValue = $value;
        return $this;
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    public function getLocalKey()
    {
        return $this->localKey;
    }

}
