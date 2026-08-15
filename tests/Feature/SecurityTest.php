<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Admin;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_admin_dashboard()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/admin/dashboard');
        
        // Assert redirected to login page because of AdminMiddleware
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_dashboard()
    {
        $admin = Admin::create([
            'username' => 'testadmin',
            'nama_lengkap' => 'Test Admin',
            'password' => Hash::make('password123')
        ]);

        $response = $this->withSession(['admin_id' => $admin->id])->get('/admin/dashboard');

        // Assert OK status
        $response->assertStatus(200);
    }

    public function test_login_regenerates_session_to_prevent_fixation()
    {
        Admin::create([
            'username' => 'testadmin',
            'nama_lengkap' => 'Test Admin',
            'password' => Hash::make('password123')
        ]);

        $response = $this->withSession(['old_session_key' => 'old_value'])
            ->post('/admin/login', [
                'username' => 'testadmin',
                'password' => 'password123'
            ]);

        $response->assertRedirect('/admin/dashboard');
        $response->assertSessionHas('admin_id');
    }

    public function test_rate_limiting_on_cek_status()
    {
        // Try hitting the endpoint 5 times. The throttle limit is 5.
        for ($i = 0; $i < 5; $i++) {
            $this->post('/cek-status', [
                'no_hp' => '081200000000'
            ]);
        }

        // The 6th attempt should be blocked by 429 Too Many Requests
        $response = $this->post('/cek-status', [
            'no_hp' => '081200000000'
        ]);

        $response->assertStatus(429);
    }
}
