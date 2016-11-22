<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EventWebRequest extends Request
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
            'name' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'event_Time' => 'required',
            'venue' => 'required',
            'description' => 'required',
            'banner' => 'required|mimes:jpg,jpeg',
        ];
    }
}
