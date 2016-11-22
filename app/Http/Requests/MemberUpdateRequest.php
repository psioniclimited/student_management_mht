<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MemberUpdateRequest extends Request
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
            'fullname' => 'required',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'addrs' => 'required',
            'mob_num' => 'required|numeric|min:11',
            'off_num' => 'required|numeric|min:6',
            'email' => 'required|email',
            'member_type' => 'required',
            'password' => 'min:6',
            'password_confirmation' => 'min:6|same:password',
        ];
    }
}