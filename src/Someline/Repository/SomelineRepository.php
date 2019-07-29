<?php


namespace Someline\Repository;


use Someline\Repository\Eloquent\EloquentRepository;
use Someline\Repository\Presenters\FractalPresenter;

abstract class SomelineRepository extends EloquentRepository
{

    public function presenter()
    {
        return new FractalPresenter($this->transformerClass());
    }

}