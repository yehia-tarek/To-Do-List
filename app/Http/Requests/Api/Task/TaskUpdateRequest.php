<?php

namespace App\Http\Requests\Api\Task;

use App\Enum\PriorityEnum;
use App\Traits\Api\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskUpdateRequest extends FormRequest
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
        $priority = implode(',', PriorityEnum::values());

        return [
            'title' => ['sometimes','required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', 'required', 'string', "in:$priority"],
            'due_date' => ['sometimes','required','date_format:Y-m-d', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.date_format' => 'Due date must be in yyyy-mm-dd format',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->returnValidationError(422, $validator));
    }
}
