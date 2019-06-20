<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       // As the $this->authorize('update', $project); was a trait on the controller we have to use
       return Gate::allows('update',$this->project());

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ];
    }

    /**
     * Returns the path to the project
     */
    public function project(){

        // If you dont want to pass the Project as route model binding then you have to manually search for the project
        return Project::findOrFail($this->route('project'));
        //return $this->route('project');
    }

    /**
     *  This will persists the form data
     */
    public function save(){

        //$this->project()->update($this->validated());
        // Or
//        $project = $this->project();
//
//        We store it in a variable as it will return boolean
//        $project->update($this->validated());
//
//        return $project;
        // Or
        return tap($this->project())->update($this->validated());

    }
}
