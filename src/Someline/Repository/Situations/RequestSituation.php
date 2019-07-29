<?php


namespace Someline\Repository\Situations;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Someline\Repository\RepositoryInterface;
use Someline\Repository\Situations\BaseSituation;

class RequestSituation extends BaseSituation
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * RequestRule constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function build(RepositoryInterface $repository, Builder $queryBuilder): Builder
    {
        $filterKey = 'filter';
        $filterText = $this->request->get($filterKey);
        if (!empty($filterText)) {
            // filter=products.country.name~china
            $operators = [
                '<=' => '<:',
                '>=' => '>:',
                '!=' => '!:',
                '=' => ':',
                '<>' => '<>',
                '>' => '>',
                '<' => '<',
                'like' => '~',
                'in' => '@',
            ];
            $filters = explode('|', $filterText);
            foreach ($filters as $filter) {
                foreach ($operators as $operator => $symbol) {
                    $results = explode($symbol, $filter, 2);
                    $results = array_diff($results, ['', null]);
                    if (count($results) === 2) {
                        [$key, $value] = $results;
                        if ($operator === 'like') {
                            $value = '%' . $value . '%';
                        }
//                        dd($value, $operator, $results);

                        $relation = null;
                        if (Str::contains($key, '.')) {
                            $explode = explode('.', $key);
                            $field = array_pop($explode);
                            $relation = implode('.', $explode);
                        } else {
                            $field = $this->buildKeyWithTable($queryBuilder, $key);
                        }

                        if ($relation !== null) {
                            $queryBuilder = $queryBuilder->whereHas($relation, function ($query) use ($field, $operator, $value) {
                                $this->buildWhereCondition($query, $field, $operator, $value);
                            });
                        } else {
                            $queryBuilder = $this->buildWhereCondition($queryBuilder, $field, $operator, $value);
                        }

                        break;
                    }
                }
            }
        }

        $orderByKey = 'orderBy';
        $sortByKey = 'sortBy';
        $orderBy = $this->request->get($orderByKey);
        $sortBy = $this->request->get($sortByKey, 'asc');
        if (!empty($orderBy)) {
            // order=products.country.id:asc|created_at:desc
            $queryBuilder = $queryBuilder->orderBy($this->buildKeyWithTable($queryBuilder, $orderBy), $sortBy);
        }
        return $queryBuilder;
    }

    protected function buildWhereCondition(Builder $queryBuilder, $field, $operator, $value): Builder
    {
        if ($operator === 'in') {
            $value = explode(',', $value);
            $queryBuilder = $queryBuilder->whereIn($field, $value);
        } else {
            $queryBuilder = $queryBuilder->where($field, $operator, $value);
        }
        return $queryBuilder;
    }

    protected function buildKeyWithTable(Builder $queryBuilder, string $key)
    {
        if (!Str::contains($key, '.')) {
            $table = $queryBuilder->getModel()->getTable();
            $key = "$table.$key";
        }
        return $key;
    }

}