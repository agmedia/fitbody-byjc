<?php

namespace App\Models\Helpers;

use App\Models\Front\Blog;
use App\Models\Front\Category\Category;
use App\Models\Front\Manufacturer;
use App\Models\Front\Product;
use Illuminate\Support\Str;

class Url
{

    /**
     * @param string $type
     * @param int    $id
     *
     * @return string
     */
    public static function set(string $type, int $id): string
    {
        //
        if ($type == 'category') {
            $category = Category::find($id);

            if ($category && $category->parent_id == 0) {
                return route('gcp_route', ['group' => Str::slug($category->group), 'cat' => $category->slug]);
            }

            $parent = Category::find($category->parent_id);

            return route('gcp_route', ['group' => Str::slug($category->group), 'cat' => $parent->slug, 'subcat' => $category->slug]);
        }

        //
        if ($type == 'page') {
            $page = Blog::find($id);

            if (isset($page->subcat)) {
                return route('blogovi', ['cat' => $page->cat->slug, 'subcat' => $page->subcat->slug, 'blog' => $page->slug]);
            }

            return route('blogovi', ['cat' => $page->cat->slug, 'subcat' => $page->slug]);
        }

        // If type is not found.
        return '';
    }

}