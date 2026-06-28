<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notificacion;
use App\Services\NotificacionService;

class EnviarRecordatorios extends Command
{
    protected $signature = 'app:enviar-recordatorios';

    protected $description = 'Envía recordatorios diarios a estudiantes con perfil incompleto y nuevas ofertas.';

    public function handle(NotificacionService $notificacionService)
    {
        $estudiantes = User::where('rol', 'estudiante')->get();

        foreach ($estudiantes as $estudiante) {
            // Recordatorio 1: nuevas ofertas
            $notificacionService->crearSiNoExisteHoy(
                $estudiante->id,
                'Nuevas ofertas disponibles',
                'Revisá las ofertas laborales publicadas hoy.',
                route('notificaciones.historial'),
                Notificacion::TIPO_INFO
            );

            // Recordatorio 2: completar perfil
            $notificacionService->crearSiNoExisteHoy(
                $estudiante->id,
                'Completá tu perfil',
                'Tu perfil incompleto reduce tus chances de ser preseleccionado.',
                route('configuracion'),
                Notificacion::TIPO_WARNING
            );
        }

        // Recordatorio a todos los admins: revisar nuevas ofertas
        User::where('rol', 'admin')->each(function ($admin) use ($notificacionService) {
            $notificacionService->crearSiNoExisteHoy(
                $admin->id,
                'Revisá las nuevas ofertas',
                'Hay ofertas publicadas hoy pendientes de revisión.',
                route('admin.ofertas'),
                Notificacion::TIPO_WARNING
            );
        });

        $this->info("Recordatorios enviados a {$estudiantes->count()} estudiantes.");

        return Command::SUCCESS;
    }
}