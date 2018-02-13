<?php

namespace Emotion\Gateway;

use Illuminate\Database\Query\Builder;

abstract class AbstractGateway
{
    /**
     * @var Builder
     */
    private $table;

    public function __construct(
        Builder $table
    ) {
        $this->table = $table;
    }

    /**
     * @param $set
     * @return bool
     */
    public function insert($set)
    {
        return $this->table->insertGetId($set);
    }

    /**
     * @param $set
     * @return int
     */
    public function update($set)
    {
        return $this->table->update($set);
    }

    /**
     * @param $select
     * @return Builder|static
     */
    public function select($select)
    {
        return $this->table->selectRaw($select);
    }
}