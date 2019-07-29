<?php

namespace Someline\Repository\Rules;

use Illuminate\Database\Eloquent\Builder;
use Someline\Repository\RepositoryInterface;

interface RuleInterface
{

    public function build(RepositoryInterface $repository, Builder $queryBuilder): Builder;

}