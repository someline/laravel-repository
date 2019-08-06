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
        return $this->repository->paginate()->present();
    }

    public function show(Request $request, $id)
    {
        return $this->repository->find($id)->present();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        return $this->repository->save($data)->present();
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        return $this->repository->update($id, $data)->present();
    }

    public function destroy(Request $request, $id)
    {
        return $this->repository->destroy($id)->present();
    }

    public function restore(Request $request, $id)
    {
        return $this->repository->restore($id)->present();
    }

}
