<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetRequestTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, array $over = []): User
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'status' => 'activo',
            'codigo_empleado' => (string) fake()->unique()->numberBetween(100000, 999999),
            'password_reset_requested_at' => null,
        ], $over));
    }

    public function test_forgot_marks_request_and_redirects(): void
    {
        $user = $this->makeUser('cocinero');

        $this->post(route('password.forgot.post'), ['Usuario' => $user->name])
            ->assertRedirect(route('login'));

        $this->assertNotNull($user->fresh()->password_reset_requested_at);
    }

    public function test_admin_cannot_change_password_without_request(): void
    {
        $admin = $this->makeUser('admin');
        $target = $this->makeUser('cocinero');

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), [
                'name' => $target->name,
                'codigo_empleado' => $target->codigo_empleado,
                'role' => 'cocinero',
                'status' => 'activo',
                'password' => 'nuevaclave123',
                'password_confirmation' => 'nuevaclave123',
            ])
            ->assertForbidden();
    }

    public function test_admin_can_change_password_with_request_and_flag_clears(): void
    {
        $admin = $this->makeUser('admin');
        $target = $this->makeUser('cocinero', ['password_reset_requested_at' => now()]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target), [
                'name' => $target->name,
                'codigo_empleado' => $target->codigo_empleado,
                'role' => 'cocinero',
                'status' => 'activo',
                'password' => 'nuevaclave123',
                'password_confirmation' => 'nuevaclave123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $fresh = $target->fresh();
        $this->assertTrue(Hash::check('nuevaclave123', $fresh->password));
        $this->assertNull($fresh->password_reset_requested_at);
    }

    public function test_sistemas_keeps_direct_password_change(): void
    {
        $sys = $this->makeUser('sistemas');
        $target = $this->makeUser('cocinero');

        $this->actingAs($sys)
            ->put(route('admin.users.update', $target), [
                'name' => $target->name,
                'codigo_empleado' => $target->codigo_empleado,
                'role' => 'cocinero',
                'status' => 'activo',
                'password' => 'otraclave123',
                'password_confirmation' => 'otraclave123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue(Hash::check('otraclave123', $target->fresh()->password));
    }

    public function test_edit_view_locked_and_unlocked(): void
    {
        $admin = $this->makeUser('admin');
        $plain = $this->makeUser('cocinero');
        $flagged = $this->makeUser('mesero', ['password_reset_requested_at' => now()]);

        $this->actingAs($admin)->get(route('admin.users.edit', $plain))
            ->assertOk()
            ->assertSee('Bloqueado');

        $this->actingAs($admin)->get(route('admin.users.edit', $flagged))
            ->assertOk()
            ->assertSee('Cambiar');
    }

    public function test_admin_does_not_see_privileged_users_but_sistemas_does(): void
    {
        $admin = $this->makeUser('admin');
        $sys = $this->makeUser('sistemas');
        $operative = $this->makeUser('cocinero');

        $asAdmin = $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $asAdmin->assertDontSee($sys->name);
        $asAdmin->assertSee($operative->name);

        $asSys = $this->actingAs($sys)->get(route('admin.users.index'))->assertOk();
        $asSys->assertSee($admin->name);
    }
}
