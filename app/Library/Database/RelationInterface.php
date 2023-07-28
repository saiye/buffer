<?php

namespace App\Library\Database;

interface RelationInterface
{
    public function get();
    public function getForeignKey();
    public function getLocalKey();

    public function setForeignKeyValue($value);
}
