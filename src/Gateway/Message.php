<?php
namespace Emotion\Gateway;

use Illuminate\Database\Query\Builder;
use Slim\Collection;

class Message extends AbstractGateway
{
    /**
     * @var Builder
     */
    private $table;
    /**
     * @var Collection
     */
    private $settings;

    public function __construct(
        Builder $table,
        Collection $settings
    ) {
        $this->table = $table;
        $this->settings = $settings;
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

    /**
     * @param null $pageNum
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMessagePagination($pageNum = null)
    {
        return $this->table->orderBy('id', 'desc')->paginate($this->settings['pagination']['perPage'], ['*'], 'page', $pageNum);
    }

}