<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{
    use WithFaker,RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_create_project()
    {
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

    // Test to see if the post request has a title
    public function test_a_project_require_a_title(){

        $this->withExceptionHandling();

        $this->post('/projects',[])->assertSessionHasErrors('title');

    }

    // Test to see if the post request has a description
    public function test_a_project_require_a_description(){

        $this->post('/projects',[])->assertSessionHasErrors('description');

    }
}
