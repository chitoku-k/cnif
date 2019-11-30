<?php

namespace App\Model\Table;

use App\Model\Behavior\LampagerBehavior;
use Cake\ORM\Table;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addBehavior(LampagerBehavior::class);
    }
}
