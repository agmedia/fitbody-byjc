<?php

namespace App\Http\Controllers\Api\v1;


use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        $query = (new User())->newQuery();

        if ($request->has('query')) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
            $query->orWhere('email', 'like', '%' . $request->input('query') . '%');
        }

        $users = $query->groupBy('id')->get();

        return response()->json($users);
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function notificationsRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 200]);
    }*/
}
