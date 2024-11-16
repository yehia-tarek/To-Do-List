<?php

namespace App\Http\Requests\Api\Attachment;

use App\Traits\Api\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAttachmentRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => [
                'required',
                'file',
                'max:10240', // 10MB max file size
                'mimes:jpeg,png,pdf,doc,docx,xls,xlsx,zip,txt',
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->returnValidationError(422, $validator));
    }
}
