<?php

namespace App\Controller;

use App\ORM\Query as AppQuery;
use Cake\ORM\TableRegistry;
use Lampager\ArrayProcessor;

class ArticlesController extends AppController
{
    public function index($direction = null)
    {
        $previous = json_decode($this->request->getQuery('previous_cursor'), true);
        $next = json_decode($this->request->getQuery('next_cursor'), true);
        $cursor = $previous ?: $next ?: [];

        $articles = TableRegistry::getTableLocator()->get('Articles');

        /** @var AppQuery $query */
        $query = $articles->lampager();
        $query
            ->forward($direction === 'next')
            ->cursor($cursor)
            ->seekable(false)
            ->order(['created' => 'DESC'])
            ->order(['id' => 'ASC'])
            ->limit(15);

        // TODO: Temporarily using ArrayProcessor directly here. Needs refactoring.
        $articles = (new ArrayProcessor())->process($query->_paginator->configure($cursor), $query->toArray());
        $this->set('articles', $articles);
        $this->set('_serialize', ['articles']);
    }
}
