<?php

namespace App;

use App\ORM\Query;
use Lampager\Concerns\HasProcessor;
use Lampager\Contracts\Cursor;
use Lampager\PaginationResult;
use Lampager\Paginator as BasePaginator;
use Lampager\Query as LampagerQuery;
use Lampager\Query\Condition;
use Lampager\Query\ConditionGroup;
use Lampager\Query\Select;
use Lampager\Query\SelectOrUnionAll;
use Lampager\Query\UnionAll;

class Paginator extends BasePaginator
{
    use HasProcessor;

    /** @var LampagerQuery $query */
    public $query;

    /** @var Query $builder */
    public $builder;

    /**
     * @return static
     */
    public static function create(Query $builder)
    {
        return new static($builder);
    }

    public function __construct(Query $builder)
    {
        $this->builder = $builder;
        $this->processor = new ArrayProcessor();
    }

    /**
     * @param  LampagerQuery $query
     * @return Query
     */
    public function transform(LampagerQuery $query)
    {
        return $this->compileSelectOrUnionAll($query->selectOrUnionAll());
    }

    /**
     * Configure -> Transform.
     *
     * @param Cursor|int[]|string[] $curosr
     */
    public function build($cursor = [])
    {
        $this->query = $this->configure($cursor);
        $this->transform($this->query);
    }

    /**
     * @return mixed|PaginationResult
     */
    public function paginate()
    {
        return $this->process($this->query, $this->builder->toArray());
    }

    /**
     * @param  SelectOrUnionAll $selectOrUnionAll
     * @return Query
     */
    protected function compileSelectOrUnionAll(SelectOrUnionAll $selectOrUnionAll)
    {
        if ($selectOrUnionAll instanceof Select) {
            return $this->compileSelect($this->builder, $selectOrUnionAll);
        }
        if ($selectOrUnionAll instanceof UnionAll) {
            $supportQuery = $this->compileSelect($this->builder, $selectOrUnionAll->supportQuery());
            $mainQuery = $this->compileSelect(clone $this->builder, $selectOrUnionAll->mainQuery());
            return $supportQuery->unionAll($mainQuery);
        }
        // @codeCoverageIgnoreStart
        throw new \LogicException('Unreachable here');
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param  Query  $builder
     * @param  Select $select
     * @return Query
     */
    protected function compileSelect($builder, Select $select)
    {
        $this
            ->compileWhere($builder, $select)
            ->compileOrderBy($builder, $select)
            ->compileLimit($builder, $select);
        return $builder;
    }

    /**
     * @param  Query  $builder
     * @param  Select $select
     * @return $this
     */
    protected function compileWhere($builder, Select $select)
    {
        $conditions = [];
        foreach ($select->where() as $group) {
            $conditions['OR'][] = iterator_to_array($this->compileWhereGroup($group));
        }
        $builder->where($conditions);
        return $this;
    }

    /**
     * @param  ConditionGroup     $group
     * @return \Generator<string,string>
     */
    protected function compileWhereGroup(ConditionGroup $group)
    {
        /** @var Condition $condition */
        foreach ($group as $condition) {
            $column = $condition->left() . ' ' . $condition->comparator();
            $value = $condition->right();
            yield $column => $value;
        }
    }

    /**
     * @param  Query  $builder
     * @param  Select $select
     * @return $this
     */
    protected function compileOrderBy($builder, Select $select)
    {
        foreach ($select->orders() as $i => $order) {
            $builder->order([$order->column() => $order->order()], $i === 0);
        }
        return $this;
    }

    /**
     * @param  Query  $builder
     * @param  Select $select
     * @return $this
     */
    protected function compileLimit($builder, Select $select)
    {
        $builder->limit($select->limit()->toInteger());
        return $this;
    }
}
