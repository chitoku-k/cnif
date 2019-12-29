<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Lampager\Cake\Model\Behavior\LampagerBehavior;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addBehavior(LampagerBehavior::class);
    }
}
