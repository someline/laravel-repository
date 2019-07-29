<?php


namespace Someline\Repository\Eloquent;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Someline\Repository\LaravelRepositoryServiceProvider;
use Someline\Repository\Presenters\PresenterInterface;

trait BuilderResultTrait
{

    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PresenterInterface
     */
    protected $presenter;

    protected $result;

    protected $isHandledResult = false;

    /**
     * Paginate the given query.
     *
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'])
    {
        $this->applySituations();
        $this->autoIncludes();
//        $this->applyCriteria();
//        $this->applyScope();
        $perPageKey = 'limit';
        if ($perPage === null) {
            $perPage = $this->request->get($perPageKey);
            if (empty($perPage)) {
                $perPage = empty($perPage) ? LaravelRepositoryServiceProvider::getConfig('pagination.perPage', 15) : $perPage;
            }
        }
        $results = $this->queryBuilder->paginate($perPage, $columns);
        $results->appends($this->request->query());
//        $this->resetModel();
        $this->result = $results;
        return $this;
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     * @return $this
     */
    public function find($id, $columns = ['*'])
    {
        $this->result = $this->queryBuilder->findOrFail($id, $columns);
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function save(array $attributes = [])
    {
        $this->result = $this->queryBuilder->create($attributes);
        return $this;
    }

    /**
     * @param $id
     * @param array $attributes
     * @param array $options
     * @return $this
     */
    public function update($id, array $attributes = [], array $options = [])
    {
        $model = $this->queryBuilder->findOrFail($id);
        $model->update($attributes, $options);
        $this->result = $model;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     * @throws \Exception
     */
    public function destroy($id)
    {
        $model = $this->queryBuilder->findOrFail($id);
        $model->delete();
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function handleResult(\Closure $closure)
    {
        $this->result = $closure($this->result);
        return $this;
    }

    /**
     * @return mixed
     */
    public function present()
    {
        return $this->parseResult($this->result);
    }

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function handleResultAndPresent(\Closure $closure)
    {
        $this->handleResult($closure);
        return $this->present();
    }

    /**
     * Wrapper result data
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function parseResult($result)
    {
        if (!$this->skipPresenter) {
            return $this->presenter->present($result);
        }

        return $result;
    }

}