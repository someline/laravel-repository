<?php


namespace Someline\Repository\Eloquent;


use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;

trait BuilderTrait
{

    /**
     * @var Builder
     */
    protected $queryBuilder;


    /**
     * @param Closure $closure
     * @return $this
     */
    public function useBuilder(Closure $closure)
    {
        $closure($this->queryBuilder);
        return $this;
    }

    /**
     * Add a where clause on the primary key to the query.
     *
     * @param mixed $id
     * @return $this
     */
    public function whereKey($id)
    {
        $this->queryBuilder->whereKey($id);
        return $this;
    }

    /**
     * Add a where clause on the primary key to the query.
     *
     * @param mixed $id
     * @return $this
     */
    public function whereKeyNot($id)
    {
        $this->queryBuilder->whereKeyNot($id);
        return $this;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param string|array|\Closure $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->queryBuilder->where($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * Add an "or where" clause to the query.
     *
     * @param \Closure|array|string $column
     * @param mixed $operator
     * @param mixed $value
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        $this->queryBuilder->orWhere($column, $operator, $value);
        return $this;
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @param bool $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $this->queryBuilder->whereIn($column, $values, $boolean, $not);
        return $this;
    }


    /**
     * Add a "where not in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @return $this
     */
    public function whereNotIn($column, $values, $boolean = 'and')
    {
        $this->queryBuilder->whereNotIn($column, $values, $boolean);
        return $this;
    }

}
