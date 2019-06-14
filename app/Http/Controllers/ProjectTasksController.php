<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project){

//        if(auth()->user()->isNot($project->owner)){
//            abort(403);
//        }
        // Or
        $this->authorize('update', $project);


        request()->validate([
            'body'=>'required'
        ]);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project,Task $task){

        // This will check if the task belongs to the project of the auth owner
//        if(auth()->user()->isNot($task->project->owner)){
//            abort(403);
//        }
        // Or
        $this->authorize('update', $project);

//         $attributes = request()->validate([
//            'body'=>'required'
//        ]);

//        $task->update([
//            'body' => request('body'),
//            'completed' => request()->has('completed') // In case of single checkbox, When user want to complete a task the request will have the param named in the checkbox, so doing this will work
//        ]);

        $task->update(request()->validate(['body'=>'required']));

        $task->update(['body' => request('body')]);

        $method = request('completed')?'complete':'incomplete';

        $task->$method();

        return redirect($project->path());

    }
}
