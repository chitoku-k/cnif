<?php

namespace App\Controller;

use App\ORM\Query as AppQuery;
use Cake\ORM\TableRegistry;

class ArticlesController extends AppController
{
    public function index($direction = null)
    {
        $previous = json_decode($this->request->getQuery('previous_cursor'), true);
        $next = json_decode($this->request->getQuery('next_cursor'), true);
        $cursor = $previous ?: $next ?: [];

        $articles = TableRegistry::getTableLocator()->get('Articles');

        /** @var AppQuery $query */
        $query = $articles->lampager()
            ->forward($direction === 'next')
            ->cursor($cursor)
            ->seekable(false)
            ->order(['created' => 'DESC'])
            ->order(['id' => 'ASC'])
            ->limit(15);

        $this->set('articles', $query);
        $this->set('_serialize', ['articles']);
    }
}
