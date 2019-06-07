<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    // Get the list of all the projects and display it
    public function index(){

        // Get all the project's list form the DB
        $projects = Project::all();

        // return a view with all the data (Note : you can pass the projects variable in the view to the page as data as shown
        return view('projects.index',compact('projects'));

    }

    public function store(){

        // Creates a project with a post request to projects
        Project::create(request(['title','description']));

        return redirect('/projects');

    }
}
