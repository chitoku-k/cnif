<?php

namespace App\Model\Behavior;

use App\ORM\Query;
use Cake\ORM\Behavior;

class LampagerBehavior extends Behavior
{
    public function lampager()
    {
        $query = new Query($this->getTable()->getConnection(), $this->getTable());
        $query->select();

        return $this->getTable()->callFinder('all', $query, []);
    }
}
