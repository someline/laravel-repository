<?php


namespace Someline\Repository\Eloquent;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use League\Fractal\Manager;

trait FractalIncludeTrait
{

    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * @var Manager
     */
    protected $fractal;

    public function autoIncludes()
    {
        $this->fractal = new Manager();
        $this->parseIncludes();
        $includes = $this->fractal->getRequestedIncludes();

        // find $model->excludes to exclude relation
        $newIncludes = $includes;
        if (isset($this->model->excludes) && !empty($this->model->excludes)) {
            $excludes = $this->model->excludes;
            foreach ($excludes as $exclude) {
                foreach ($newIncludes as $key => $include) {
                    if (Str::startsWith($include, $exclude . '.') || strtolower($include) == strtolower($exclude)) {
                        unset($newIncludes[$key]);
                    }
                }
            }
        }
        $newIncludes = array_values($newIncludes);

        $this->queryBuilder->with($newIncludes);
    }

    protected function parseIncludes()
    {

        $request = app('Illuminate\Http\Request');
        $paramIncludes = config('repository.fractal.params.include', 'include');

        if ($request->has($paramIncludes)) {
            $this->fractal->parseIncludes($request->get($paramIncludes));
        }

        return $this;
    }

}
