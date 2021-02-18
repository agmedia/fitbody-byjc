<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'tags';
    
    /**
     * @var array $guarded
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
