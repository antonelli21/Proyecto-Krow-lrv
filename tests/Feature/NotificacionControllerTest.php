<?php

namespace Tests\Feature;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificacionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_mark_as_read_accepts_notification_belonging_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        $notification = Notificacion::create([
            'id_usuario' => $user->id,
            'titulo' => 'Prueba',
            'mensaje' => 'Mensaje de prueba',
            'tipo' => 'info',
            'leida' => false,
        ]);

        $response = $this->actingAs($user)->postJson('/notificaciones/api/marcar-leida', [
            'id' => $notification->id,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertTrue($notification->fresh()->leida);
    }

    public function test_mark_as_read_rejects_notification_from_another_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $notification = Notificacion::create([
            'id_usuario' => $otherUser->id,
            'titulo' => 'Prueba',
            'mensaje' => 'Mensaje de prueba',
            'tipo' => 'info',
            'leida' => false,
        ]);

        $response = $this->actingAs($user)->postJson('/notificaciones/api/marcar-leida', [
            'id' => $notification->id,
        ]);

        $response->assertStatus(422);
    }
}
