<?php

namespace Someline\Repository\Eloquent;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\PresenterInterface;
use Someline\Repository\Exceptions\RepositoryException;
use Someline\Repository\RepositoryInterface;
use Someline\Repository\Rules\RuleInterface;

abstract class EloquentRepository implements RepositoryInterface
{
    use BuilderTrait;
    use BuilderResultTrait;
    use FractalIncludeTrait;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * @var PresenterInterface
     */
    protected $presenter;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Rules
     *
     * @var array
     */
    protected $rules;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var bool
     */
    protected $skipPresenter = false;

    /**
     * @var \Closure
     */
    protected $scopeQuery = null;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->rules = collect();
        $this->request = app('request');
        $this->makeModel();
        $this->makeQueryBuilder();
        $this->makePresenter();
//        $this->makeValidator();
        $this->boot();
    }

    /**
     *
     */
    public function boot()
    {
        //
    }

    /**
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Specify Eloquent Model class name
     *
     * @return string
     */
    abstract public function modelClass();

    abstract public function presenter();

    abstract public function transformerClass();

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->modelClass());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->modelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function makeQueryBuilder()
    {
        return $this->queryBuilder = $this->model->newQuery();
    }

    /**
     * @param null $presenter
     *
     * @return PresenterInterface
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function makePresenter($presenter = null)
    {
        $presenter = !is_null($presenter) ? $presenter : $this->presenter();

        if (!is_null($presenter)) {
            $this->presenter = is_string($presenter) ? $this->app->make($presenter) : $presenter;

//            if (!$this->presenter instanceof PresenterInterface) {
//                throw new RepositoryException("Class {$presenter} must be an instance of Prettus\\Repository\\Contracts\\PresenterInterface");
//            }

            return $this->presenter;
        }

        return null;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setPresenterMeta(array $meta)
    {
        if ($this->presenter) {
            $this->presenter->setMeta($meta);
        }
        return $this;
    }

    public function pushRuleViaClass(string $ruleClass)
    {
        $this->rules->push(app($ruleClass));
    }

    public function pushRule(RuleInterface $rule)
    {
        $this->rules->push($rule);
    }

    public function applyRules()
    {
        foreach ($this->rules as $rule) {
            if ($rule instanceof RuleInterface) {
                $this->queryBuilder = $rule->build($this, $this->queryBuilder);
            }
        }
        return $this;
    }

}