<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Brand;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\SaleDirectCustomerDesk;

trait SaleCustomerDeskDirectRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'customer_name',
        'desk_name',
        'email',
        'created_by'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCustomerDesk(array $data, $user)
    {
        $userToken = UserToken::where('token', $user)->first();

        $data['customer_name'] = $data['customer_name'];
        $data['desk_name'] = $data['desk_name'];
        $data['email'] = $data['email'];
        $data['created_by'] = $userToken->user_id;

        $saleCusDesk = new SaleDirectCustomerDesk(Arr::only($data, $this->keys));
        if ($saleCusDesk->save()) {
            return response()->json(['status' => true, 'message' => 'meja berhasil dibuat']);
        }

        return response()->json(['status' => false, 'message' => 'meja gagal dibuat']);
    }

    /**
     * Update the current resource.
     */
    public function updateBrand(array $data, $id)
    {
        $brand = Brand::find($id);
        $location = 'file_brand/' . uniqid();

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }

        if ($brand->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyInquiry($id)
    {
        if (Brand::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreInquiry($id)
    {
        if (Brand::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
