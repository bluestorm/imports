<?php

namespace BlueStorm\Imports\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Import
 *
 * @package BlueStorm\Imports\Models
 */
class Import extends Model
{
    /**
     * @var string
     */
    protected $table = 'imports';

    /**
     * @var string[]
     */
    protected $guarded = ['updated_at', 'deleted_at'];

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'file', 'collectionHandle', 'fieldMapping', 'fieldUnique'];

    /**
     * @var string[]
     */
    protected $casts = [
        'fieldMapping' => 'json',
        'fieldUnique' => 'json'
    ];
}
