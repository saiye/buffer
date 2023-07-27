<?php

namespace App\Library\Database;

interface RelationInterface
{
    public function get();

    public function setForeignKeyValue($value);
}
