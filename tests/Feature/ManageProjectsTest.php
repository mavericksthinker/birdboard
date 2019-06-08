<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker,RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_create_project(){

//        $this->actingAs(factory('App\User')->create());

        // Helper functions included from the TestCase.php to avoid repetition of code
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        // Stores the project into the DB ( creates a table) Passes
        $this->post('/projects',$attributes)->assertRedirect('/projects');

        // Checks if the DB has table projects Passes
        $this->assertDatabaseHas('projects',$attributes);

        // Obtains all the projects from the DB Passes
        $this->get('/projects')->assertSee($attributes['title']);
    }

    /**
     * Test to check if the view has the provided parameters
     */
    public function test_a_user_can_view_their_project(){

        // $this->be(factory('App\User')->create());

        $this->withoutExceptionHandling();
        // Helper functions included from the TestCase.php to avoid repetition of code
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );
        //$project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description, 200));

    }

    /**
     * An authenticated user cant view the projects of others
     */
    public function test_an_authenticated_user_cant_access_the_project_of_others(){

//        $this->be(factory('App\User')->create());
        // Helper functions included from the TestCase.php to avoid repetition of code
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }

    // Test to see if the post request has a title Passes
    public function test_a_project_require_a_title(){

        // Performs the sign_in
        // $this->actingAs(factory('App\User')->create());

        // Helper functions included from the TestCase.php to avoid repetition of code
        $this->signIn();

        /**
         * create : This will generate the data and store it in the database
         * make : This will generate the data but doesnt store it in the database
         * make : This will generate the data and store it in the database but will stored as an array
         */
        // Overwrite the title as shown below
        $attributes = factory('App\Project')->raw(['title'=>'']);

        $this->post('/projects',$attributes)->assertSessionHasErrors('title');

    }

    // Test to see if the post request has a description Passes
    public function test_a_project_require_a_description(){

        // Performs the sign_in
        // $this->actingAs(factory('App\User')->create());

        // Helper functions included from the TestCase.php to avoid repetition of code
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description'=>'']);

        $this->post('/projects',$attributes)->assertSessionHasErrors('description');

    }

    // Test to see if the post request has a description Passes
    public function test_guest_cannot_manage_projects(){

        $project = factory('App\Project')->create();

        //$attributes = factory('App\Project')->raw();

        // $this->post('/projects',$attributes)->assertSessionHasErrors('owner_id');
        $this->get('/projects')->assertRedirect('login');

        $this->get('/projects/create')->assertRedirect('login');

        $this->post('/projects',$project->toArray())->assertRedirect('login');

        $this->get($project->path())->assertRedirect('login');

    }

//    // Test to see if the post request has a description Passes
//    public function test_guest_may_not_view_projects(){
//
//        // $this->post('/projects',$attributes)->assertSessionHasErrors('owner_id');
//        //$this->get('/projects')->assertRedirect('login');
//
//    }
//
//    // Test to check a guest user cannot view any project
//    public function test_guest_cannot_view_a_single_project(){
//
//        //$project = factory('App\Project')->create();
//
//        // $this->post('/projects',$attributes)->assertSessionHasErrors('owner_id');
//        //$this->get($project->path())->assertRedirect('login');
//
//    }
}
