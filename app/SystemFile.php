<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    protected $table = 'system_files';
    protected $fillable = [
        'created_at',
        'updated_at',
        'deleted_at',
        'storage_path',
        'filename',
        'extension'
    ];
    protected $visible = [
        'created_at',
        'updated_at',
        'storage_path',
        'filename',
        'extension'
    ];
}
