<?php

namespace Someline\Repository\Transformers;


use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class ToArrayTransformer extends BaseTransformer
{

    public function transform(Model $model)
    {
        return $model->toArray();
    }

}