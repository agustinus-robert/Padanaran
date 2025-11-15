<?php

namespace Modules\Administration\Http\Requests\Database\Manage\User\Profile;

use Modules\Administration\Http\Requests\User\Profile\UpdateRequest as FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->user);
    }
}