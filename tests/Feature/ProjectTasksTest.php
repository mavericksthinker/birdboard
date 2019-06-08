<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_guest_cant_add_task(){

        $project = factory('App\Project')->create();

        $this->post($project->path().'/tasks')->assertRedirect('login');

    }

    public function test_only_owner_of_the_project_can_add_tasks_to_their_projects(){

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
             ->assertStatus(403);

        $this->assertDatabaseMissing('tasks',['body'=>'Test task']);
    }


    public function test_a_project_can_have_tasks()
    {
        //$this->withoutExceptionHandling();
        // Helper functions included from the TestCase.php to avoid repetition of code
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        //$project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }


    public function test_a_project_requires_a_body(){

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory('App\Task')->raw(['body'=>'']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');

    }
}
