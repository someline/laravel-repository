<?php


namespace Someline\Repository\Presenters;


interface PresenterInterface
{

    public function present($data);

    public function setMeta($meta);

}