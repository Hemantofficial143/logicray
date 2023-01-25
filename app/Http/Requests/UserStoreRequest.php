<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
        $rules = [
            'name' => ['required'],
            'email' => ['required'],
        ];
        if ($this->has('id')) {
            $rules['email'][] = Rule::unique('users', 'email')->ignore($this->id, 'id');
        } else {
            $rules['email'][] = 'unique:users';
            $rules['password'] = ['required'];
            $rules['cpassword'] = ['required'];
        }
        return $rules;
    }
}
