<?php

namespace App\Models\Settings;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    
    /**
     * @var string $table
     */
    protected $table = 'pages';
    
    /**
     * @var array $guarded
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    
    /*******************************************************************************
     *                                Copyright : AGmedia                           *
     *                              email: filip@agmedia.hr                         *
     *******************************************************************************/
    // Static functions
    
    /**
     * @return mixed
     */
    public static function getMenu()
    {
        return self::where('status', 1)->select('id', 'name')->get();
    }
    
    
    /**
     * @return mixed
     */
    public static function groups()
    {
        return self::groupBy('group')->pluck('group');
    }
    
    
    /**
     * @param $request
     *
     * @return bool
     * @throws \Exception
     */
    public static function store($request, $id = 0)
    {
        // Delete, if any action exist
        if ($id) {
            self::where('id', $id)->delete();
        }
        
        // Insert new action
        $_id = self::insertGetId([
            'name'             => $request->name,
            'slug'             => isset($request->slug) ? Str::slug($request->slug) : Str::slug($request->name),
            'description'      => $request->description,
            'seo_title'        => $request->seo_title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'group'            => $request->group,
            'status'           => (isset($request->status) and $request->status == 'on') ? 1 : 0,
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now()
        ]);
        
        if ($_id) {
            return self::find($_id);
        }
        
        return false;
    }
    
}
