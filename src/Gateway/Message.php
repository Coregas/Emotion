<?php
namespace Emotion\Gateway;

use Illuminate\Database\Query\Builder;

class Message extends AbstractGateway
{
    private $table;

    public function __construct(
        Builder $table
    )
    {
        $this->table = $table;
        parent::__construct($this->table);
    }

}