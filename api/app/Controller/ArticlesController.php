<?php

App::uses('AppController', 'Controller');

class ArticlesController extends AppController
{
    public $components = [
        'Paginator',
    ];

    public function index($direction = null)
    {
        // Get cursor parameters
        $previous = json_decode($this->request->query('previous_cursor'), true);
        $next = json_decode($this->request->query('next_cursor'), true);

        $this->Paginator->settings = [
            'forward' => $direction !== 'previous',
            'cursor' => $previous ?: $next ?: [],
            'seekable' => false,
            'order' => [
                'Article.created' => 'DESC',
                'Article.id' => 'ASC',
            ],
            'limit' => 15,
        ];

        /** @var mixed[][] */
        $articles = $this->Paginator->paginate(Article::class);
        $this->set('articles', $articles);
        $this->set('_serialize', ['articles']);
    }
}
