<?php

namespace App\Http\Requests\Api\ReauthorizationRequestDashboard;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequthorizationRequestFormRequest extends FormRequest
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
            'patient_id' => 'required|exists:patients,id',
        ];
    }
}
