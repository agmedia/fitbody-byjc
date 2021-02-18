<?php

namespace App\Models\Back\Custom;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
