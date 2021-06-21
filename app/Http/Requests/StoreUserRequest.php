<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identification_type_id'        => 'required|exists:identification_types,id',
            'first_name'                    => 'required|string|min:1|max:255',
            'last_name'                     => 'required|string|min:1|max:255',
            'email'                         => 'required|email|unique:users,email',
            'phone'                         => 'required|string',
            'birthday'                      => 'required|date|date_format:Y-m-d'
        ];
    }
}
