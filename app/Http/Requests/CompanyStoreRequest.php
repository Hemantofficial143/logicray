<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends FormRequest
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
            'users' => ['required'],
            'country' => ['required'],
        ];
        if ($this->has('id')) {
            $rules['name'][] = Rule::unique('companies', 'name')->ignore($this->id);
        } else {
            $rules['name'][] = 'unique:companies';
        }
        return $rules;
    }
}
