<?php

namespace App\Models\Back;

use App\Models\CategoryMenu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Category extends Model
{

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeClearSinglePages($query)
    {
        return $query->where('single_page', 0);
    }


    /**
     * Get the categories for the
     * navigation menu.
     *
     * @return mixed
     */
    public static function getList()
    {
        return (new CategoryMenu())->list();
    }


    /**
     * @param bool $singles
     *
     * @return mixed
     */
    public static function getListWithoutTop($singles = false)
    {
        return (new CategoryMenu())->list(false, $singles);
    }


    /**
     * Get the categories menu.
     * List for the select component.
     *
     * @param bool $admin
     *
     * @return mixed
     */
    public static function getMenu($admin = false)
    {
        return (new CategoryMenu())->menu($admin);
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function parents()
    {
        return DB::table('categories')->where('parent_id', '==', '')->pluck('name', 'id');
    }


    /**
     * Store new category.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public static function store(Request $request)
    {
        $top    = (isset($request->top) and $request->top == 'on') ? 1 : 0;
        $parent = ( ! $top and isset($request->parent)) ? intval($request->parent) : 0;
        $group  = isset($request->group) ? $request->group : 0;

        //dd($top, $parent, $group, $request);

        if ($parent) {
            $topcat = self::where('id', $parent)->first();
            $group  = $topcat->group;
        }

        //dd($top, $parent, $group, $request);

        $id = self::insertGetId([
            'name'             => $request->name,
            'description'      => $request->description,
            'meta_description' => $request->meta_description,
            'meta_keyword'     => $request->meta_keyword,
            'parent_id'        => $parent,
            'group'            => $group,
            'single_page'      => (isset($request->single_page) and $request->single_page == 'on') ? 1 : 0,
            'top'              => $top,
            'sort_order'       => $request->sort_order ? $request->sort_order : 0,
            'status'           => (isset($request->status) and $request->status == 'on') ? 1 : 0,
            'slug'             => Str::slug($request->name),
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now()
        ]);

        if ($id) {
            return self::find($id);
        }

        return false;
    }


    /**
     * Update category.
     *
     * @param Request $request
     * @param         $category
     *
     * @return mixed
     */
    public static function edit(Request $request, $category)
    {
        $top    = (isset($request->top) and $request->top == 'on') ? 1 : 0;
        $parent = ( ! $top and isset($request->parent)) ? intval($request->parent) : 0;
        $group  = isset($request->group) ? $request->group : 0;

        if ($parent) {
            $topcat = self::where('id', $parent)->first();
            $group  = $topcat->group;
        }

        $updated = self::where('id', $category->id)->update([
            'name'             => $request->name,
            'description'      => $request->description,
            'meta_description' => $request->meta_description,
            'meta_keyword'     => $request->meta_keyword,
            'parent_id'        => $parent,
            'group'            => $group,
            'single_page'      => (isset($request->single_page) and $request->single_page == 'on') ? 1 : 0,
            'top'              => $top,
            'sort_order'       => $request->sort_order ? $request->sort_order : 0,
            'status'           => (isset($request->status) and $request->status == 'on') ? 1 : 0,
            'slug'             => Str::slug($request->name),
            'updated_at'       => Carbon::now()
        ]);

        if ($updated) {
            return self::find($category->id);
        }

        return false;
    }


    /**
     * @param $category
     * @param $path
     *
     * @return mixed
     */
    public static function updateImagePath($category, $path)
    {
        return self::where('id', $category->id)->update([
            'image' => $path
        ]);
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public static function withSubDestroy($id)
    {
        $category = self::find($id);

        // If it's a Top Category
        if ( ! $category['parent_id']) {
            self::where('parent_id', $id)->delete();
        }

        return self::where('id', $id)->delete();
    }
}
