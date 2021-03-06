<?php

namespace App\Models\Back\Custom;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Price extends Model
{

    /**
     * @var string
     */
    protected $table = 'prices';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @var Request
     */
    protected $request;


    /**
     * Validate Page Request.
     *
     * @param Request $request
     *
     * @return $this
     */
    public function validateRequest(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $this->request = $request;

        return $this;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function storePrice()
    {
        $id = $this->insertGetId([
            'title'      => $this->request->title,
            'subtitle'   => $this->request->subtitle,
            'price'      => $this->request->price,
            'price_per'  => $this->request->price_per,
            'tags'       => $this->request->tags,
            'status'     => (isset($this->request->status) and $this->request->status == 'on') ? 1 : 0,
            'featured'   => (isset($this->request->featured) and $this->request->featured == 'on') ? 1 : 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($id) {
            return $this->find($id);
        }

        return false;
    }


    /**
     * @param array $id
     *
     * @return bool
     * @throws \Exception
     */
    public function updatePrice($id)
    {
        return $this->where('id', $id)->update([
            'title'      => $this->request->title,
            'subtitle'   => $this->request->subtitle,
            'price'      => $this->request->price,
            'price_per'  => $this->request->price_per,
            'tags'       => $this->request->tags,
            'status'     => (isset($this->request->status) and $this->request->status == 'on') ? 1 : 0,
            'featured'   => (isset($this->request->featured) and $this->request->featured == 'on') ? 1 : 0,
            'updated_at' => Carbon::now()
        ]);
    }
}
