<?php


namespace Someline\Repository\Eloquent;


use Illuminate\Database\Eloquent\Builder;
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
        $this->queryBuilder->with($includes);
//        dd($includes);
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