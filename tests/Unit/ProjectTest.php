<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Checks if the project model has a path
     *
     * @return void
     */
    public function test_project_model_has_path(){

        $project = factory('App\Project')->create();

        $this->assertEquals('/projects/'. $project->id,$project->path());
    }

    /**
     * Tests if the project belongs to the owner
     */
    public function test_project_belongs_to_an_owner(){

        $project = factory('App\Project')->create();

        $this->assertInstanceOf('App\User',$project->owner);


    }

    public function test_it_can_add_task(){

        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        // To test that the DB at_least returning a single project
        $this->assertCount(1, $project->tasks);

        // To test that the DB Return the specified project
        $this->assertTrue($project->tasks->contains($task));

    }
}
