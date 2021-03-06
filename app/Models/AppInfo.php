<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class AppInfo
{

    /**
     * @return false
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function get()
    {
        if (Storage::disk('local')->exists('app_info.json')) {
            $data = json_decode(
                Storage::disk('local')->get('app_info.json')
            );

            return $data->info;
        }

        return self::set([]);
    }


    /**
     * @param array $data
     *
     * @return bool
     */
    public static function set(array $data)
    {
        unset($data['_token']);

        return Storage::disk('local')->put('app_info.json', json_encode(['info' => $data], JSON_PRETTY_PRINT));
    }

}
