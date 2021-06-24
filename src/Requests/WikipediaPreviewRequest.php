<?php

namespace Litstack\Wikipedia\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class WikipediaPreviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
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
            'url'     => 'required|url|regex:/(http[s]*\/\/)?(([^.]+)\.)?wikipedia\.org(.*)/',
            'section' => 'string|nullable',
            'chars'   => 'integer|nullable',
        ];
    }
}
