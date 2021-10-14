<?php

namespace BlueStorm\Imports\Repositories;

/**
 * Interface ImportInterface
 * @package BlueStorm\Imports\Repositories
 */
interface ImportInterface
{
    public function getAll();

    public function store($request);

    public function update($import, $request);

    public function storeMappedFields($import, $request);

    public function destroy($import);
}




