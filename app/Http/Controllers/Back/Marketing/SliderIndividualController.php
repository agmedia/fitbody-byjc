<?php

namespace App\Http\Controllers\Back\Marketing;

use App\Models\Back\Category;
use App\Models\Back\Marketing\Blog\Blog;
use App\Models\Back\Marketing\Slider\Slider;
use App\Models\Back\Marketing\Slider\SliderGroup;
use App\Models\Back\Product\Product;
use App\Models\Back\Settings\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Bouncer;

class SliderIndividualController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $group = SliderGroup::find($id);

        return view('back.marketing.slider.edit-individual', compact('group'));
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
        $slider = new Slider();
        $stored = $slider->validateRequest($request)->store();

        if ($stored) {
            if ($request->has('image') && $request->input('image')) {
                $slider->resolveImage($stored->id);
            }

            return redirect()->route('slider.edit', ['id' => $request->input('group')])->with(['success' => 'Slider was succesfully saved!']);
        }

        return redirect()->back()->with(['error' => 'Whoops..! There was an error creating the slider.']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $sid)
    {
        $group = SliderGroup::find($id);

        $slider = Slider::find($sid);

        if ( ! $slider) {
            abort(401);
        }

        return view('back.marketing.slider.edit-individual', compact('group', 'slider'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $sid)
    {
        $slider = new Slider();
        $updated = $slider->validateRequest($request)->resave($sid);

        if ($updated) {
            if ($request->has('image') && $request->input('image')) {
                $slider->resolveImage($sid);
            }

            return redirect()->route('slider.edit', ['id' => $id])->with(['success' => 'Slider was succesfully updated!']);
        }

        return redirect()->back()->with(['error' => 'Whoops..! There was an error updating the slider.']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        if (isset($request['data']['id'])) {
            if (Bouncer::is(auth()->user())->an('admin')) {
                Cache::forget('home_sl');
            }

            return response()->json(
                SliderGroup::destroyAll($request['data']['id'])
            );
        }
    }
}
