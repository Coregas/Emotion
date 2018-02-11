<?php
namespace Emotion\Gateway;
use Illuminate\Database\Query\Builder;

class User
{
    private $table;

    public function __construct(
        Builder $table
    ) {
        $this->table = $table;
    }

    public function getOneUser()
    {
        return $this->table->find(1);
    }
}