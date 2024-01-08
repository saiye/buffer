<?php

namespace App\Library\Database;
class Model implements ModelInterface
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table;
    protected $columns=[];
    protected $where=[];
    protected $order=[];
    protected $limit=1;
    protected $with=[];

    public function table($table): Model
    {
        $this->table = $table;
        return $this;
    }

    public function select(...$columns): Model
    {
        $this->columns = $columns;
        return $this;
    }

    public function where($column, $operator, $value = null): Model
    {
        if (is_null($value)) {
            $value    = $operator;
            $operator = '=';
        }
        $this->where[] = [$column, $operator, $value];
        return $this;
    }

    public function orderBy($column, $direction = 'ASC'): Model
    {
        $this->order[] = [$column, $direction];
        return $this;
    }

    public function limit($limit): Model
    {
        $this->limit = $limit;
        return $this;
    }

    public function getClient(): Connection
    {
        return new Connection($this);
    }

    /**
     * @throws \Throwable
     */
    public function get()
    {
      return $this->getClient()->get();
    }

    public function first()
    {
        return $this->getClient()->first();
    }

    public function find($id)
    {
        return $this->getClient()->find($id);
    }

    /**
     * @throws \Exception
     */
    public function findOrFail($id)
    {
        return $this->getClient()->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->getClient()->create($data);
    }

    public function update(array $data): int
    {
        return $this->getClient()->update($data);
    }

    /**
     * @throws \Exception
     */
    public function delete():int
    {
        return $this->getClient()->delete();
    }

    public function hasOne($related, $foreignKey = null, $localKey = null): HasOne
    {
        return new HasOne($related, $foreignKey, $localKey);
    }

    public function hasMany($related, $foreignKey = null, $localKey = null): HasMany
    {
        return new HasMany($related, $foreignKey, $localKey);
    }

    public function with($with): Model
    {
        if (is_array($with)) {
            $this->with = array_merge($this->with, $with);
        } else {
            $this->with[] = $with;
        }
        return $this;
    }

    public function beginTransaction(): bool
    {
        return $this->getClient()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->getClient()->commit();
    }

    public function rollBack(): bool
    {
        return $this->getClient()->rollBack();
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    public function getConnection(): string
    {
       return $this->connection;
    }

    public function getPrimaryKey(): string
    {
       return $this->primaryKey;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getWhere(): array
    {
        return $this->where;
    }

    public function getOrder(): array
    {
        return $this->order;
    }

    public function getLimit(): int
    {
        return  $this->limit;
    }

    public function getWith(): array
    {
        return $this->with;
    }
}
