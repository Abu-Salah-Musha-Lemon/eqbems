<?php

test('registration screen can be rendered', function () {
    config()->set('features.public_registration', true);
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    config()->set('features.public_registration', true);
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('public registration is disabled by default', function () {
    config()->set('features.public_registration', false);
    $this->get('/register')->assertNotFound();
    $this->post('/register')->assertNotFound();
});
