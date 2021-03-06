<?php

namespace App\Models\Back;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * @var string
     */
    protected $table = 'tags';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * @param $tag
     *
     * @return mixed
     */
    public static function store($tag)
    {
        $_tag = self::firstOrCreate([
            'name' => $tag
        ]);

        if (isset($_tag->id)) {
            return $_tag->id;
        }

        return false;
    }
}
