<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    protected $guarded = [];

    public $old = [];

    public function path(){

        return "/projects/{$this->id}";

    }

    public function owner(){

        return $this->belongsTo(User::class);

    }

    public function tasks(){

        return $this->hasMany(Task::class);

    }

    public function addTask($body){

       return $this->tasks()->create(compact('body'));

    }

    /**
     * @param $description
     */
    public function recordActivity($description){

        //$this->activity()->create(['description' => $description]);
        // Is same as
        //$this->activity()->create(compact('description'));
//        $this->activity()->create([
//            'description' => $description,
//            'changes' => [
//                'before' => array_diff($this->old, $this->getAttributes()),
//                'after' => array_diff($this->getAttributes(), $this->old)
//            ]
//        ]);
        // Or
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges()
        ]);
        // As we already has a relationship
//        Activity::create([
//            'project_id' => $this->id,
//            'description' => $type
//        ]);

    }

    public function activityChanges()
    {
        if($this->wasChanged())
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()),'updated_at'),
                'after' => Arr::except($this->getChanges(),'updated_at')
            ];
    }

    public function activity(){

       return $this->hasMany(Activity::class)->latest();

    }
}
