<?php

namespace Someline\Repository\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseApiController extends Controller
{

    /**
     * @var \Someline\Repository\RepositoryInterface
     */
    protected $repository;


    public function index(Request $request)
    {
        return $this->repository->setPresenterMeta(['b' => 123])->paginate()->handleResult(function ($result) {
            dump($result);
            return $result;
        })->present();
    }

    public function get(Request $request, $id)
    {
        return $this->repository->setPresenterMeta(['b' => 123])->find($id)->present();
    }

    public function save(Request $request)
    {
        $data = $request->all();
        return $this->repository->setPresenterMeta(['b' => 123])->save($data)->present();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return $this->repository->setPresenterMeta(['b' => 123])->update($id, $data)->present();
    }

    public function destroy(Request $request, $id)
    {
        return $this->repository->setPresenterMeta(['b' => 123])->destroy($id)->present();
    }

}