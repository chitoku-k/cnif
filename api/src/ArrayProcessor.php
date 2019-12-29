<?php

namespace App;

use App\ORM\Query;
use Lampager\ArrayProcessor as BaseArrayProcessor;
use Lampager\Query as LampagerQuery;

class ArrayProcessor extends BaseArrayProcessor
{
    protected function makeCursor(LampagerQuery $query, $row)
    {
        /** @var Query $builder */
        $builder = $query->builder();
        $alias = $builder->getRepository()->getAlias();

        $cursor = [];

        foreach ($query->orders() as $order) {
            if (isset($row[$order->column()])) {
                $cursor[$order->column()] = $row[$order->column()];
                continue;
            }

            $column = str_replace("{$alias}.", '', $order->column());

            if (isset($row[$column])) {
                $cursor[$order->column()] = $row[$column];
                continue;
            }
        }

        return $cursor;
    }
}
