<?php

App::uses('Controller', 'Controller');

class AppController extends Controller
{
    public $components = [
        'RequestHandler',
    ];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->RequestHandler->renderAs($this, 'json');
    }
}
