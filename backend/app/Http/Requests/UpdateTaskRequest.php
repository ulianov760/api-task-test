<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
            ],
            'name' => [
                'required',
                'string',
                'min:8',
                'max:250',
                Rule::unique('tasks', 'name')->ignore(request()->id),
            ],
            'description' => [
                'required',
                'string',
                'min:4',
                'max:5000',
            ],
            'status' => [
                'required',
                'string',
                'min:3',
                'max:250',
            ],
            'date_expiration' => [
                'required',
                'date',
                'after:now'
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'errors' => $validator->errors(),
                ],
                Response::HTTP_BAD_REQUEST
            )
        );
    }
}
