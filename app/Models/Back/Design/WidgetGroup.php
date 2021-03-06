<?php

namespace App\Models\Back\Design;

use App\Models\Helpers\Url;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class WidgetGroup extends Model
{

    /**
     * @var string
     */
    protected $table = 'widget_groups';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var
     */
    private $url;


    /**
     * @param $query
     *
     * @return mixed
     */
    public function widgets()
    {
        return $this->hasMany(Widget::class, 'group_id', 'id');
    }


    /**
     * @param Request $request
     *
     * @return $this
     */
    public function validateRequest(Request $request)
    {
        $request->validate([
            'section' => 'required',
            'title'   => 'required'
        ]);

        $this->setRequest($request);

        return $this;
    }


    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSectionsList()
    {
        $blades   = new \DirectoryIterator('./../resources/views/front/layouts/widgets');
        $response = [];

        foreach ($blades as $file) {
            if (strpos($file, 'blade.php') !== false) {
                $filename = str_replace('.blade.php', '', $file);

                $response[] = [
                    'id'    => str_replace('widget_', '', $filename),
                    'title' => str_replace('widget_', 'Dizajn ', $filename)
                ];
            }
        }

        return $response;
    }


    /**
     * @return mixed
     */
    public function store()
    {
        $id = $this->insertGetId([
            'section_id' => $this->request->section,
            'title'      => $this->request->title,
            'slug'       => Str::slug($this->request->title),
            'width'      => $this->request->width ? $this->request->width : null,
            'status'     => (isset($this->request->status) and $this->request->status == 'on') ? 1 : 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $this->find($id);
    }


    /**
     * @param $id
     *
     * @return false
     */
    public function edit($id)
    {
        $ok = $this->where('id', $id)->update([
            'section_id' => $this->request->section,
            'title'      => $this->request->title,
            'slug'       => Str::slug($this->request->title),
            'width'      => $this->request->width ? $this->request->width : null,
            'status'     => (isset($this->request->status) and $this->request->status == 'on') ? 1 : 0,
            'updated_at' => Carbon::now()
        ]);

        if ($ok) {
            return $this->find($id);
        }

        return false;
    }


    /**
     * @return array[]
     */
    public function sizes()
    {
        return [
            [
                'value' => 12,
                'title' => '1:1 - Puna ??irina'
            ],
            [
                'value' => 6,
                'title' => '1:2 - Pola ??irine'
            ],
            [
                'value' => 4,
                'title' => '1:3 - Tre??ina ??irine'
            ],
            [
                'value' => 8,
                'title' => '2:3 - 2 tre??ine ??irine'
            ],
        ];
    }


    /**
     * Set Product Model request variable.
     *
     * @param $request
     */
    private function setRequest($request)
    {
        $this->request = $request;
    }

}