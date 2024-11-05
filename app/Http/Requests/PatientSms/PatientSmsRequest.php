<?php

namespace App\Http\Requests\PatientSms;

use Illuminate\Foundation\Http\FormRequest;

class PatientSmsRequest extends FormRequest
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
            'read' => 'nullable|string',
            'unread' => 'nullable|string',
            'archived' => 'nullable|string',
        ];
    }
}
