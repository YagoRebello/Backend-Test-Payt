<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\Redirect;
use App\Models\RedirectLog;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_redirect_with_valid_url()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://google.com',
            'status' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('redirects', ['url_destino' => 'https://google.com']);
    }

    public function test_create_redirect_with_invalid_url_dns()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'http://invalid-url',
            'status' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_create_redirect_with_invalid_url_format()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'google.com', // URL inválida
            'status' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_create_redirect_with_invalid_url_app()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'http://127.0.0.1:8000/redirects',
            'status' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_create_redirect_with_non_https_url()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'http://example.com',
            'status' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_create_redirect_with_non_200_201_status()
    {
        Http::fake([
            'http://example.com' => Http::response(null, 404),
        ]);

        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'http://example.com',
            'status' => true,
        ]);

        $response->assertStatus(422);
    }

    public function test_create_redirect_with_empty_query_params()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://google.com',
            'status' => true,
            'query_params' => ['' => 'value'], // Chave vazia
        ]);

        $response->assertStatus(422);
    }

    public function test_stats_access()
    {
        $redirect = Redirect::factory()->create();

        // Criar dois logs de acesso com IPs diferentes
        RedirectLog::factory()->create(['redirect_id' => $redirect->id, 'ip' => '192.168.1.1']);
        RedirectLog::factory()->create(['redirect_id' => $redirect->id, 'ip' => '192.168.1.2']);

        $response = $this->get("/api/redirects/stats/{$redirect->id}");

        $response->assertStatus(200)
            ->assertJson([
                'totalAccesses' => 2,
                'uniqueAccesses' => 2, // Agora esperamos 2 acessos únicos
            ]);
    }

    public function test_query_params_merge_two_origins()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://google.com',
            'status' => true,
        ]);

        $redirectId = $response->json('id');

        $response = $this->get("/api/redirects/{$redirectId}?utm_source=facebook&utm_campaign=ads");
        $response->assertStatus(302) // Redirecionamento esperado
        ->assertRedirect('https://google.com?utm_source=facebook&utm_campaign=ads');
    }

    public function test_query_params_prioritize_request()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://google.com',
            'status' => true,
            'query_params' => ['utm_source' => 'facebook'],
        ]);

        $redirectId = $response->json('id');

        $response = $this->get("/api/redirects/{$redirectId}?utm_source=instagram&utm_campaign=ads");
        $response->assertStatus(302) // Redirecionamento esperado
        ->assertRedirect('https://google.com?utm_source=instagram&utm_campaign=ads');
    }

    public function test_query_params_ignore_empty_key_request()
    {
        $response = $this->postJson('/api/redirects', [
            'url_destino' => 'https://google.com',
            'status' => true,
            'query_params' => ['utm_source' => '', 'utm_campaign' => 'test'],
        ]);

        $redirectId = $response->json('id');

        $response = $this->get("/api/redirects/{$redirectId}?utm_source=facebook");
        $response->assertStatus(302) // Redirecionamento esperado
        ->assertRedirect('https://google.com?utm_campaign=test');
    }
}
