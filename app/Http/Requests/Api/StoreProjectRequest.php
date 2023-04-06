<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth('sanctum')->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'name' => ['required', 'string', 'max:30',
                Rule::unique('projects')->where(function ($query) {
                    $query->where('user_id', $this->user_id)->where('name', $this->name);
                })->ignore($this->project)
            ],
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
            'wt_visibility' => 'nullable|boolean',
            'name_field_visibility' => 'nullable|boolean',
            'email_field_visibility' => 'nullable|boolean',
            'welcome_text' => 'nullable|max:255',
            'question' => 'nullable|max:255',
            'comment' => 'nullable|max:255',
        ];
    }

}
