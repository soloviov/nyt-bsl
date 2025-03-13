<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\DTO\HistoryRequestDto;

class GetHistory extends FormRequest
{
    public function rules(): array
    {
        return [
            'author' => [
                'string',
                'sometimes',
                'nullable',
            ],
            'isbn' => [
                'array',
                'sometimes',
                'nullable',
            ],
            'isbn.*' => [
                'string',
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\d{10}$|^\d{13}$/', $value)) {
                        $fail('The ISBN must be 10 or 13 digits.');
                    }
                },
            ],
            'title' => [
                'string',
                'sometimes',
                'nullable',
            ],
            'offset' => [
                'integer',
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value % 20 !== 0) {
                        $fail('The offset must be a multiple of 20.');
                    }
                },
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'author' => [
                'description' => 'The author of the best seller',
                'example' => 'Stephen Hawking',
            ],
            'isbn' => [
                'description' => 'International Standard Book Number, 10 or 13 digits',
                'example' => [
                    '0553380168',
                    '9780553380163',
                ],
            ],
            'title' => [
                'description' => 'The title of the best seller',
                'example' => 'A BRIEF HISTORY OF TIME',
            ],
            'offset' => [
                'description' => 'Sets the starting point of the result set (must be a multiple of 20)',
                'example' => 0,
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function dto(): HistoryRequestDto
    {
        return HistoryRequestDto::fromArray($this->validated());
    }
}
