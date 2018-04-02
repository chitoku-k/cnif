<?php

Router::connect('/articles/:direction/*', [
    'controller' => 'articles',
    'action' => 'index',
], [
    'direction' => 'next|previous',
    'pass' => ['direction'],
]);

Router::connect('/:controller');
Router::connect('/:controller/:action');
