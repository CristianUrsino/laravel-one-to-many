<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['required', 'min:3','max:255', Rule::unique('projects')->ignore($this->project)],
            'description'=>['required', 'min:3', 'max:65535'],
            'repository_link'=>['required', 'url','min:3', Rule::unique('projects')->ignore($this->project)],
            'completion_date'=>['nullable', 'date'],
            'project_status'=>['required'],
        ];
    }

    public function messages(){
        return [
            'name.required' => 'mandatory "name"',
            'name.min' => 'name must have at least :min characters',
            'name.max' => 'name a maximum :max characters',
            'name.unique' => ' name already exists',
            'description.required' => 'mandatory "description"',
            'description.min' => 'description must have at least :min characters',
            'description.max' => 'description must have a maximum:max characters',
            'repository_link.required' => 'mandatory "repository link"',
            'repository_link.unique' => ' repository_link already exists',
            'repository_link.url' => 'the link must be a url',
            'completion_date.date' => 'completion date must be a date',
            'project_status'=> 'mandatory "project status"'
        ];
    }
}
