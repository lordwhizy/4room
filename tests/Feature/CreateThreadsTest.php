<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());

    }

    /** @test */
    function an_authenticated_user_can_create_forum_thread()
    {
        // Given we have an authenticated user
        $this->actingAs(factory('App\User')->create());
        // when we hit the endpoint to create a new thread
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
        // Then when we visit the thread page.
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
