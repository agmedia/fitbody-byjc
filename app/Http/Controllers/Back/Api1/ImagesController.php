<?php

namespace App\Http\Controllers\Back\Api1;


use App\Models\Back\Photo;
use App\Models\Back\Product\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{

    /**
     * Upload images on Product update.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesUpload(Request $request)
    {
        $files = $request->file('file');
        $type_id = $request->input('type_id') ? $request->input('type_id') : 0;

        $paths = [];

        if (is_array($files) and $type_id) {
            foreach ($files as $file) {
                $path = Storage::disk($request->input('type'))->putFileAs($type_id, $file, $file->getClientOriginalName());
                $paths[] = config('filesystems.disks.gallery.url') . $request->input('type') . '/' . $path;
            }

            if ($request->input('type') == 'products') {
                ProductImage::saveStack($paths, $type_id);
            }

            return response()->json($paths);
        }

        return false;
    }


    /**
     * Upload temporary images to
     * products /temp folder.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesUploadTemporary(Request $request)
    {
        $files = $request->file('file');

        $paths = [];

        if (is_array($files)) {
            foreach ($files as $file) {
                $path = Storage::disk($request->input('type'))->putFileAs('temp', $file, $file->getClientOriginalName());
                $paths[] = config('filesystems.disks.gallery.url') . $request->input('type') . '/' . $path;
            }

            if ($request->input('type') == 'products') {
                session(['product_temporary_images' => $paths]);
            } else {
                session(['temporary_images' => $paths]);
            }
        }

        return response()->json($paths);
    }


    /**
     * Save an edited image to disk.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesUploadEdited(Request $request)
    {
        try {
            Storage::disk($request->type)->putFileAs($request->type_id, $request->image, $request->name);

            return response()->json(['success' => 'Fotografija uspje??no ure??ena!']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        return response()->json($request);
    }


    /**
     * Upload images with ajax.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesAjaxUpload(Request $request)
    {
        if ($request->get('data')) {

        }
        Log::info($request);

        return response()->json(['id' => 'OK!']);

        $files = $request->file('file');
        $type_id = $request->input('type_id') ? $request->input('type_id') : 0;

        $paths = [];

        if (is_array($files) and $type_id) {
            foreach ($files as $file) {
                $path = Storage::disk($request->input('type'))->putFileAs($type_id, $file, $file->getClientOriginalName());
                $paths[] = config('filesystems.disks.gallery.url') . $request->input('type') . '/' . $path;
            }

            if ($request->input('type') == 'products') {
                ProductImage::saveStack($paths, $type_id);
            }

            return response()->json($paths);
        }

        /*if ( ! is_array($files)) {
            $path = Storage::disk('gallery')->putFileAs($request->input('type'), $files, $files->getClientOriginalName());
            $path = config('filesystems.disks.gallery.url') . $path;

            if ($request->input('type') == 'slider') {
                $id = Slider::bootWithImage($path);
            }

            return response()->json(['id' => $id]);
        }*/

        return false;
    }


    /**
     * Set default image on a product.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDefault(Request $request)
    {
        $updated = 0;

        if ($request->type == 'products') {
            $path = config('filesystems.disks.products.url') . $request->type_id . '/' . $request->name;

            $updated = ProductImage::setDefault($path, $request->type_id);
        }

        if ($updated) {
            return response()->json(['success' => 'Fotografija uspje??no postavljena!']);
        }

        return response()->json(['message' => 'Gre??ka kod postavljanja fotografije! Poku??ajte ponovo.']);
    }


    /**
     * Destroy an Image.
     * Could be from products...
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $name = Photo::getName($request);

        try {
            Storage::disk($request->type)->delete($request->type_id . '/' . $name);

            if ($request->type == 'products') {
                ProductImage::destroy($request->id);
            }
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        return response()->json($request);
    }


}
