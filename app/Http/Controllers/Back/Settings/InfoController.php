<?php

namespace App\Http\Controllers\Back\Settings;

use App\Models\AppInfo;
use App\Models\Back\Settings\Plan;
use App\Models\Back\Users\Client;
use Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class InfoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AppInfo::get();

        return view('back.settings.info.edit', compact('data'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $stored = AppInfo::set($request->toArray());

        if ($stored) {
            Cache::forget('app_info');

            return redirect()->back()->with(['success' => 'Aplikacijske postavke su uspješno snimljene..!']);
        }

        return redirect()->back()->with(['error' => 'Whoops..! Dogodila se greška prilikom snimanja aplikacijskih postavki.']);
    }

}
