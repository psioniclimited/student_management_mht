<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request
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
            'pcode' => 'required',
            'pname' => 'required',
            'uprice' => 'required',
            'item_type' => 'required',
            'pg_id' => 'required',
            'psg_id' => 'required',
            'pu_id' => 'required',
            'coa_id' => 'required',            
        ];
    }
}
