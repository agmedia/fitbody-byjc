<?php

namespace App\Models\Back\Marketing\Slider;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SliderGroup extends Model
{

    /**
     * @var string
     */
    protected $table = 'slider_groups';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sliders()
    {
        return $this->hasMany(Slider::class, 'group_id')->orderBy('sort_order');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sliders_count()
    {
        return $this->hasMany(Slider::class, 'group_id')->count();
    }


    /**
     * @param $request
     *
     * @return bool
     * @throws \Exception
     */
    public static function store($request)
    {
        return self::insert([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'page_id'     => $request->page_id,
            'blog_id'     => $request->blog_id,
            'date_start'  => new Carbon($request->date_start),
            'date_end'    => new Carbon($request->date_end),
            'status'      => (isset($request->status) and $request->status == 'on') ? 1 : 0,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now()
        ]);
    }


    /**
     * @param $request
     * @param $id
     *
     * @return bool
     * @throws \Exception
     */
    public static function edit($request, $id)
    {
        return self::where('id', $id)->update([
            'name'        => $request->name,
            'category_id' => $request->categories,
            'page_id'     => $request->pages,
            'blog_id'     => 0,
            'date_start'  => '',//new Carbon($request->date_start),
            'date_end'    => '',//new Carbon($request->date_end),
            'status'      => (isset($request->status) and $request->status == 'on') ? 1 : 0,
            'updated_at'  => Carbon::now()
        ]);
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public static function destroyAll($id)
    {
        self::where('id', $id)->delete();

        return Slider::where('group_id', $id)->delete();
    }
}
