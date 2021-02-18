<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Slider extends Model
{

    /**
     * @var string
     */
    protected $table = 'sliders';

    /**
     * @var array
     */
    protected $fillable = [];


    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeHome($query)
    {
        return $this->where('group_id', 1)->orderBy('sort_order');
    }
}
