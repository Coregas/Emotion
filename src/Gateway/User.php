<?php
namespace Emotion\Gateway;
use Illuminate\Database\Query\Builder;

class User extends AbstractGateway
{
    private $table;

    public function __construct(
        Builder $table
    ) {
        $this->table = $table;
        parent::__construct($this->table);
    }

    /**
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserById($userId)
    {
        return $this->table->where('id', '=', $userId)->get()->first();
    }
}