<?php
namespace Emotion\Gateway;

use Illuminate\Database\Query\Builder;

class Message extends AbstractGateway
{
    private $table;

    public function __construct(
        Builder $table
    ) {
        $this->table = $table;
        parent::__construct($this->table);
    }

    /**
     * @param $messageId
     * @return mixed
     */
    public function getMessageById($messageId)
    {
        return $this->table->where('id', '=', $messageId)->get()->first();
    }

}