<?php

namespace Lampager\Cake\ORM;

use Cake\Database\Expression\OrderByExpression;
use Cake\Database\Expression\OrderClauseExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\ExpressionInterface;
use Cake\ORM\Query as BaseQuery;
use Lampager\Cake\Paginator;
use Lampager\Contracts\Cursor;

class Query extends BaseQuery
{
    /** @var Paginator */
    protected $_paginator;

    /** @var Cursor|int[]|string[] */
    protected $_cursor = [];

    /**
     * @param \Cake\Database\Connection $connection The connection object
     * @param \Cake\ORM\Table           $table      The table this query is starting on
     */
    public function __construct($connection, $table)
    {
        parent::__construct($connection, $table);

        $this->_paginator = Paginator::create($this);
    }

    /**
     * @param BaseQuery $query
     */
    public static function fromQuery($query)
    {
        $obj = new static($query->getConnection(), $query->getRepository());

        foreach (get_object_vars($query) as $k => $v) {
            $obj->$k = $v;
        }

        $obj->_executeOrder($obj->clause('order'));
        $obj->_executeLimit($obj->clause('limit'));

        return $obj;
    }

    /**
     * @param Cursor[]|int[]|string[] $cursor
     */
    public function cursor($cursor = [])
    {
        $this->_cursor = $cursor;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function order($fields, $overwrite = false)
    {
        parent::order($fields, $overwrite);
        $this->_executeOrder($this->clause('order'));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function orderAsc($field, $overwrite = false)
    {
        parent::orderAsc($field, $overwrite);
        $this->_executeOrder($this->clause('order'));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function orderDesc($field, $overwrite = false)
    {
        parent::orderDesc($field, $overwrite);
        $this->_executeOrder($this->clause('order'));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function limit($num)
    {
        parent::limit($num);
        $this->_executeLimit($this->clause('limit'));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        if ($this->_results !== null) {
            return $this->_results;
        }

        parent::all();

        return $this->_results = $this->_paginator->paginate();
    }

    /**
     * {@inheritDoc}
     */
    protected function _transformQuery()
    {
        if (!$this->_dirty || $this->_type !== 'select') {
            return;
        }

        parent::_transformQuery();

        $this->_paginator->build($this->_cursor);
    }

    /**
     * @param  null|OrderByExpression $order
     * @return void
     */
    protected function _executeOrder($order)
    {
        $this->_paginator->clearOrderBy();

        if ($order === null) {
            return;
        }

        $generator = $this->getValueBinder();
        $order->iterateParts(function ($condition, $key) use ($generator) {
            if (!is_int($key)) {
                /**
                 * @var string $key       The column
                 * @var string $condition The order
                 */
                $this->_paginator->orderBy($key, $condition);
            }

            if ($condition instanceof OrderClauseExpression) {
                $generator->resetCount();

                if (!preg_match('/ (?<direction>ASC|DESC)$/', $condition->sql($generator), $matches)) {
                    throw new \LogicException('OrderClauseExpression does not have direction');
                }

                /** @var string $direction */
                $direction = $matches['direction'];
                $field = $condition->getField();

                if ($field instanceof ExpressionInterface) {
                    $generator->resetCount();
                    $this->_paginator->orderBy($field->sql($generator), $direction);
                } else {
                    $this->_paginator->orderBy($field, $direction);
                }
            }

            if ($condition instanceof QueryExpression) {
                $generator->resetCount();
                $this->_paginator->orderBy($condition->sql($generator));
            }

            return $condition;
        });
    }

    /**
     * @param  null|int|QueryExpression $limit
     * @return void
     */
    protected function _executeLimit($limit)
    {
        if ($limit === null) {
            return;
        }

        if (is_int($limit)) {
            $this->_paginator->limit($limit);
            return;
        }

        if ($limit instanceof QueryExpression) {
            $generator = $this->getValueBinder();
            $generator->resetCount();
            $this->_paginator->limit($limit->sql($generator));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function __call($method, $args)
    {
        static $options = [
            'forward',
            'backward',
            'exclusive',
            'inclusive',
            'seekable',
            'unseekable',
            'fromArray',
        ];

        if (in_array($method, $options, true)) {
            $this->_paginator->$method(...$args);
            return $this;
        }

        return parent::__call($method, $args);
    }
}
