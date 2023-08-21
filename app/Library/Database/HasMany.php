<?php

namespace App\Library\Database;

class HasMany implements RelationInterface
{
    use RelationRrait;
    protected $relatedModel;
    protected $foreignKey;

    protected $localKey;
    protected $foreignKeyValue;



    public function __construct($relatedModel, $foreignKey, $localKey)
    {
        $this->relatedModel = new $relatedModel();
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    public function get()
    {
        if ($this->foreignKeyValue){
            return $this->relatedModel->where($this->foreignKey, 'in',$this->foreignKeyValue)->keyBy($this->foreignKey,true)->get();
        }
        return [];
    }


}

