<?php

namespace App\Http\Controllers;

use App\Project;
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
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
        // Creates a project with a post request to projects
        //Project::create(request(['title','description'])
        //$attributes['owner_id'] = Auth()->id();

        auth()->user()->projects()->create($attributes);

        //Project::create($attributes);

        // then it will redirect to the projects with a get request to obtain all the list of the project
        return redirect('/projects');

    }

    /**
     * @param Project $project
     * @return View
     */
    // route model binding (will auto inject the project related to the wild card
    public function show(Project $project){

        // find the particular project based on the id provided obtain from the database
        // Or else if not found it will throw the exception
//        $project =Project::findOrFail(request('project'));

        // Forbidden to access the projects not related to the owner
        if(auth()->user()->isNot($project->owner)){
            abort(403);
        }

        // Returns a view with obtained project details
        return view('projects.show',compact('project'));

    }
}
