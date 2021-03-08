<?php

namespace App\Http\Controllers\Back\Design;

use App\Models\Back\Catalog\Manufacturer;
use App\Models\Back\Category;
use App\Models\Back\Design\Widget;
use App\Models\Back\Design\WidgetGroup;
use App\Models\Back\Marketing\Blog\Blog;
use App\Models\Back\Product\Product;
use App\Models\Back\Settings\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WidgetGroupController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $wg = new WidgetGroup();
        $sizes = $wg->sizes();
        $sections = $wg->getSectionsList();

        return view('back.design.widgets.edit-group', compact('sizes', 'sections'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $wg = new WidgetGroup();
        $stored = $wg->validateRequest($request)->store();

        if ($stored) {
            return redirect()->back()->with(['success' => 'Widget grupa je uspješno snimljena!']);
        }

        return redirect()->back()->with(['error' => 'Whoops..! Desila se greška sa snimanjem widget grupe.']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $query = (new WidgetGroup())->newQuery();

        $widget = $query->where('id', $id)->first();

        if ( ! $widget) {
            abort(401);
        }

        $wg = new WidgetGroup();
        $sizes = $wg->sizes();
        $sections = $wg->getSectionsList();

        return view('back.design.widgets.edit-group', compact('widget', 'sections', 'sizes'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $widget = new Widget();
        $updated = $widget->validateRequest($request)->setUrl()->edit($id);

        if ($updated) {
            if (Widget::hasImage($request)) {
                $updated->resolveImage($request);
            }

            return redirect()->back()->with(['success' => 'Widget je uspješno snimljen!']);
        }

        return redirect()->back()->with(['error' => 'Whoops..! Desila se greška sa snimanjem widgeta.']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (isset($request['data']['id'])) {
            return response()->json(
                Widget::where('id', $request['data']['id'])->delete()
            );
        }
    }


    /*******************************************************************************
    *                                Copyright : AGmedia                           *
    *                              email: filip@agmedia.hr                         *
    *******************************************************************************/
    // API ROUTES

    public function getLinks(Request $request)
    {
        if ($request->has('type')) {
            if ($request->input('type') == 'category') {
                return response()->json(Category::getList());
            }
            if ($request->input('type') == 'manufacturer') {
                return response()->json(Manufacturer::list());
            }
            if ($request->input('type') == 'product') {
                return response()->json(Product::getMenu());
            }
            if ($request->input('type') == 'page') {
                return response()->json(Blog::published()->pluck('title', 'id'));
            }
        }

        return response()->json([
            'id' => 0,
            'text' => 'Molimo odaberite tip linka..'
        ]);
    }
}
