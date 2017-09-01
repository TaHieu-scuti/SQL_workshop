<?php

namespace Tests\Feature;

use App\User;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @return \App\User
     */
    private function getUser()
    {
        return (new User)->find(1)->first();
    }

    public function testLoginRouteReturnsStatus200WhenNotLoggedIn()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testLoginRouteReturnsCorrectViewWhenNotLoggedIn()
    {
        $response = $this->get('/login');

        $response->assertViewIs('auth.login');
    }

    public function testDefaultRouteRedirectsToLoginWhenNotLoggedIn()
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function testLoginRouteRedirectsToHomeRouteWhenLoggedIn()
    {
        $response = $this->actingAs($this->getUser())
                         ->get('/login');

        $response->assertRedirect('/home');
    }

    public function testDefaultRouteRedirectsToLoginRouteWhenLoggedIn()
    {
        $response = $this->actingAs($this->getUser())
                         ->get('/');

        $response->assertRedirect('/login');
    }

    public function testLogoutRouteRedirectsToDefaultRouteWhenNotLoggedIn()
    {
        $response = $this->get('/logout');

        $response->assertRedirect('/');
    }

    public function testLogoutRouteRedirectsToDefaultRouteWhenLoggedIn()
    {
        $response = $this->actingAs($this->getUser())
                         ->get('/logout');

        $response->assertRedirect('/');
    }
}
