<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Requests\Customer;

use DoubleThreeDigital\SimpleCommerce\Http\Requests\AcceptsFormRequests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use AcceptsFormRequests;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($formRequest = $this->get('_request')) {
            return $this->buildFormRequest($formRequest, $this)->rules();
        }

        return [];
    }
}
