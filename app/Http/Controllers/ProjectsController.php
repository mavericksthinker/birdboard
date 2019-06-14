<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    /**
     * Get the list of all the projects and display it
     * @return View
     */
    public function index(){

        // Get all the project's list form the DB
        // $projects = auth()->user()->projects()->orderBy('updated_at','desc')->get();
        // or if we add the orderBy in the User model in projects() then (Just to make it cleaner)
        $projects = auth()->user()->projects;

        // return a view with all the data (Note : you can pass the projects variable in the view to the page as data as shown
        return view('projects.index',compact('projects'));

    }

    /**
     * Function utilized to create a project
     */
    public function create(){

        return view('projects.create');

    }

    /*
     * Creates a project and stores it in the database
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(){

        // Validate the request parameters if it exists (can be done the way you require)
//        $attributes = request()->validate([
//            'title' => 'required',
//            'description' => 'required',
//            'notes' => 'min:3'
//        ]);
        // Creates a project with a post request to projects
        //Project::create(request(['title','description'])
        //$attributes['owner_id'] = Auth()->id();

        $project = auth()->user()->projects()->create($this->validateRequest());

        //Project::create($attributes);

        // then it will redirect to the projects with a get request to obtain all the list of the project
        // return redirect('/projects');

        return redirect($project->path());
    }

    /**
     * @param Project $project
     * @return View
     * @throws AuthorizationException
     */
    // route model binding (will auto inject the project related to the wild card
    public function show(Project $project){

        // find the particular project based on the id provided obtain from the database
        // Or else if not found it will throw the exception
//        $project =Project::findOrFail(request('project'));

        // Forbidden to access the projects not related to the owner
//        if(auth()->user()->isNot($project->owner)){
//            abort(403);
//        }
        // Or
        $this->authorize('update', $project);


        // Returns a view with obtained project details
        return view('projects.show',compact('project'));

    }

    public function edit(Project $project){

        return view('projects.edit',compact('project'));

    }

    public function update(Project $project){

        $this->authorize('update', $project);

//        $attributes = request()->validate([
//            'title' => 'sometimes|required',
//            'description' => 'sometimes|required',
//            'notes' => 'nullable'
//        ]);
        // Same as $this->authorize('update', $project); but without policy
//        if(auth()->user()->isNot($project->owner)){
//            abort(403);
//        }

        // $project->update([
          //  'notes' => request('notes')
        // ]);
        // Or
        // $project->update(request('notes')); : This will give the value of notes associated with notes wrapping it with [] will return key and value( i.e notes => value ) associated with the notes
//        $project->update($attributes);

        // Making the validation reusable
        $project->update($this->validateRequest());

        return redirect($project->path());
    }

       // Or

       // Via a validation request
//       public function update(UpdateProjectRequest $request,Project $project){
//
//        // $project->update($request->validated());
//        // Or
//           $request->save();
//
//        // If you dont want to pass the Project as route model binding then you have to manually search for the project
//        // return redirect($request->project()->path());
//
//        return redirect($project->path());
//
//       }

//    // Via a validation request but if we dont want to include Project $project via route model binding
//    public function update(UpdateProjectRequest $form){
//
//        return redirect($form->save()->path());
//
//    }

    /**
     * Validate the request attributes
     */
    protected function validateRequest(){

        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);

    }

}
