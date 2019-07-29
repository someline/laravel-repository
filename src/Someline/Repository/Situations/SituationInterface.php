<?php

namespace Someline\Repository\Situations;

use Illuminate\Database\Eloquent\Builder;
use Someline\Repository\RepositoryInterface;

interface SituationInterface
{

    public function build(RepositoryInterface $repository, Builder $queryBuilder): Builder;

}