<?php

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    // It will reference any relationships, any belongs to relationship or any belongs to relationships that you want to touch if this model is updated
    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static function boot(){

        parent::boot();

        // Can be done via observer
        static::created(function($task){

            $task->project->recordActivity('created_task');

        });

        // Been taken care of in complete()
//        static::updated(function($task){
//
//            if(!$task->completed) return;
//
//            $task->project->recordActivity('completed_task');
//        });
    }

    public function project(){

        return $this->belongsTo(Project::class);

    }

    public function path(){

        return "/projects/{$this->project->id}/tasks/{$this->id}";

    }

    public function complete(){

        $this->update(['completed' => true]);

        $this->project->recordActivity('completed_task');

    }

    public function incomplete(){

        $this->update(['completed' => false]);

        //$this->project->recordActivity('task_marked_incomplete');

    }
}
