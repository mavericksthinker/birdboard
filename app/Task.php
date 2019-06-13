<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    // It will reference any relationships, any belongs to relationship or any belongs to relationships that you want to touch if this model is updated
    protected $touches = ['project'];

    public function project(){

        return $this->belongsTo(Project::class);

    }

    public function path(){

        return "/projects/{$this->project->id}/tasks/{$this->id}";

    }
}
