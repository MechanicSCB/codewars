<?php

namespace App\Http\Requests;

use App\Models\Kata;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKataRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->kata->id ?? Kata::generateId(),
            'verified' => $this->verified ?? null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                'string',
                'size:24',
                Rule::unique('katas')->ignore($this->kata),
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                //Rule::unique('katas')->ignore($this->kata->id, 'slug'),
                Rule::unique('katas')->ignore($this->kata),
            ],
            'slug' => [
                'nullable',
                //'required',
                'string',
                'min:3',
                Rule::unique('katas')->ignore($this->kata),
            ],
            'description' => 'required|string|min:10',
            'rank' => [
                'required',
                Rule::in(config('codewars.ranks')),
            ],
            'category' => [
                'required',
                Rule::in(config('codewars.categories')),
            ],
            'tags' => [
                'nullable',
                //Rule::in(config('codewars.tags')),
            ],
            //'coauthors_wanted' => 'nullable',
            //'languages' => [
            //    'required',
            //    Rule::in(array_column(config('codewars.languages'), 'slug')),
            //],
            'verified' => 'nullable',
            'solution' => 'nullable|min:3',
            'sample_test' => 'nullable',
            'random_test' => 'nullable|json',
            'created_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
        ];
    }
}
