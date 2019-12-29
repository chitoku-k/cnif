<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Lampager\Cake\Datasource\Paginator;
use Lampager\Ckae\ORM\Query;

class ArticlesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator', [
            'paginator' => new Paginator(),
        ]);
    }

    public function behavior($direction = null)
    {
        $previous = json_decode($this->request->getQuery('previous_cursor'), true);
        $next = json_decode($this->request->getQuery('next_cursor'), true);
        $cursor = $previous ?: $next ?: [];

        $articles = TableRegistry::getTableLocator()->get('Articles');

        /** @var Query $query */
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

    public function component($direction = null)
    {
        $previous = json_decode($this->request->getQuery('previous_cursor'), true);
        $next = json_decode($this->request->getQuery('next_cursor'), true);
        $cursor = $previous ?: $next ?: [];

        $this->set('articles', $this->paginate('Articles', [
            'forward' => $direction === 'next',
            'cursor' => $cursor,
            'seekable' => false,
            'order' => [
                'Articles.created' => 'DESC',
                'Articles.id' => 'ASC',
            ],
            'limit' => 15,
        ]));
        $this->set('_serialize', ['articles']);
    }
}
