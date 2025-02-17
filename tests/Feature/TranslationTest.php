<?php

use App\Models\Translation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\{postJson, putJson, getJson};

beforeEach(function () {
    // Create and authenticate a user
    Sanctum::actingAs(User::factory()->create());
});

test('can create a translation successfully', function () {
    $data = [
        'locale' => 'en',
        'key' => 'welcome_message',
        'content' => 'Welcome to our platform!',
        'tag' => 'web',
    ];

    postJson('/api/translations', $data)
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Translation added',
            'data' => [
                'locale' => 'en',
                'key' => 'welcome_message',
                'content' => 'Welcome to our platform!',
            ]
        ]);
});

test('cannot create translation with invalid data', function () {
    $data = [
        'locale' => 'invalid_locale',
        'key' => '',
        'content' => '',
        'tag' => str_repeat('a', 51), // Exceeding max length
    ];

    postJson('/api/translations', $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['locale', 'key', 'content', 'tag']);
});

test('can update a translation', function () {
    $translation = Translation::factory()->create();

    $updateData = [
        'locale' => 'fr',
        'key' => $translation->key,
        'content' => 'Bienvenue sur notre plateforme!',
        'tag' => 'mobile',
    ];

    putJson("/api/translations/{$translation->id}", $updateData)
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Translation updated',
            'data' => [
                'locale' => 'fr',
                'content' => 'Bienvenue sur notre plateforme!',
            ]
        ]);
});

test('can fetch all translations', function () {
    Translation::factory(10)->create();

    getJson('/api/translations')
        ->assertStatus(200)
        ->assertJsonStructure(['data']);
});

test('can fetch translations by filters', function () {
    Translation::factory()->create(['locale' => 'es', 'tag' => 'web']);

    getJson('/api/translations?locale=es&tag=web')
        ->assertStatus(200)
        ->assertJsonFragment(['locale' => 'es', 'tag' => 'web']);
});

test('json export endpoint returns correct data', function () {
    Translation::factory()->create(['locale' => 'en', 'key' => 'greeting', 'content' => 'Hello']);

    getJson('/api/translations/export')
        ->assertStatus(200)
        ->assertJsonFragment(['greeting' => 'Hello']);
});
