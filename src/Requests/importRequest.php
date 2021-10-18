<?php

namespace BlueStorm\Imports\Requests;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ImportRequest
 * @package BlueStorm\Imports\Requests
 */
class ImportRequest extends FormRequest {

    /**
     * @return string[]
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'file' => 'required|mimetypes:text/csv,xml,json',
            'collectionHandle' => 'required',
        ];
    }

}
