<?php

namespace App\Http\Requests\Api\Task;

use App\Enum\PriorityEnum;
use App\Models\Project;
use App\Traits\Api\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
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
            'project_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $project = Project::where('user_id', $this->user()->id)->find($value);

                    if (!$project) {
                        $fail('Project not found for this user');
                    }
                }
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', 'required', 'string', "in:$priority"],
            'due_date' => ['required','date_format:Y-m-d', 'after_or_equal:today'],
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
