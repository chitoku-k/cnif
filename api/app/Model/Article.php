<?php

App::uses('AppModel', 'Model');

use Lampager\ArrayProcessor;
use Lampager\PaginationResult;
use Lampager\Query;

class Article extends AppModel
{
    public function beforeFind($query)
    {
        ArrayProcessor::setDefaultFormatter(function ($rows, array $meta, Query $query) {
            return new PaginationResult(Hash::extract($rows, '{n}.Article'), $meta);
        });

        return parent::beforeFind($query);
    }
}
