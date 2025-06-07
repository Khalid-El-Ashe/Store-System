<?php

namespace App\Http\Requests;

use App\Rules\Filter;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all for now; add your authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Get the category ID from the route (for unique validation ignore)
        $id = $this->route('category');

        // Blacklist example for custom rule, commented out for now
        $blackLists = ['laravel', 'admin', 'manager', 'user', 'language'];

        return [
            // 'name' => "required|string|min:5|max:100|unique:categories,name,$id",
            // You can uncomment and use your custom Filter rule if implemented
            'name' => [
                'required',
                'string',
                'min:5',
                'max:100',
                "unique:categories,name,$id",
                new Filter($blackLists)
            ],
            'description' => 'nullable|string|min:20|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif,svg,webp|max:1000',
            'status' => 'required|in:active,archived',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'This name is already in use!',
            'name.required' => 'The name field is required!',
            'name.min' => 'The name must be at least 5 characters.',
            'name.max' => 'The name may not be greater than 100 characters.',
            'description.min' => 'The description must be at least 20 characters.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'image.mimes' => 'The image must be a file of type: jpeg, jpg, png, gif.',
            'image.max' => 'The image may not be greater than 1000 kilobytes.',
            'status.required' => 'Status field is required.',
            'status.in' => 'Status must be either active or archived.',
            'parent_id.integer' => 'Parent category ID must be an integer.',
            'parent_id.exists' => 'Selected parent category does not exist.',
        ];
    }
}
