<?php

namespace BlueStorm\Imports\Repositories;
use BlueStorm\Imports\Models\Import;

/**
 * Class ImportRepository
 * @package BlueStorm\Imports\Repositories
 */
class ImportRepository implements ImportInterface
{
    private $import;

    /**
     * ImportRepository constructor.
     * @param Import $import
     */
    function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * @return Import[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->import->all();
    }

    /**
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $this->import->name = $request['name'];
        $this->import->file = $request['file'][0];
        $this->import->collectionHandle = $request['collectionHandle'][0];
        return $this->import->save();
    }

    /**
     * @param $import
     * @param $request
     * @return mixed
     */
    public function update($import, $request)
    {
        $import->name = $request['name'];
        $import->file = $request['file'][0];
        $import->collectionHandle = $request['collectionHandle'][0];
        return $import->save();
    }

    /**
     * @param $import
     * @param $request
     * @return mixed
     */
    public function storeMappedFields($import, $request)
    {
        $import->fieldMapping = $request;
        return $import->save();
    }

    /**
     * @param $import
     * @return mixed
     */
    public function destroy($import)
    {
        return $import->delete();
    }
}
