<?php


namespace Someline\Repository;


interface RepositoryInterface
{

    /**
     * @param array $meta
     * @return $this
     */
    public function setPresenterMeta(array $meta);

    /**
     * @param array $columns
     * @return $this
     */
    public function all($columns = ['*']);

    /**
     * Paginate the given query.
     *
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*']);

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     * @return $this
     */
    public function find($id, $columns = ['*']);

    /**
     * @param array $attributes
     * @return $this
     */
    public function save(array $attributes = []);

    /**
     * @param $id
     * @param array $attributes
     * @param array $options
     * @return $this
     */
    public function update($id, array $attributes = [], array $options = []);

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id);

    /**
     * @param $id
     * @return $this
     */
    public function restore($id);

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function handleResult(\Closure $closure);

    /**
     * @return mixed
     */
    public function getResult();

    /**
     * @return mixed
     */
    public function present();

    /**
     * @param \Closure $closure
     * @return mixed
     */
    public function handleResultAndPresent(\Closure $closure);

    /**
     * Wrapper result data
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function parseResult($result);

}
