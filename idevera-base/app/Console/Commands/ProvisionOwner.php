<?php

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProvisionOwner extends Command
{
    protected $signature = 'idevera:owner {email?} {--name=}';

    protected $description = 'Crea el primer OWNER en local, sin promover una cuenta existente.';

    public function handle(): int
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->error('Este procedimiento está habilitado únicamente en local.');

            return self::FAILURE;
        }
        if (User::query()->where('role', Role::OWNER)->exists()) {
            $this->error('Ya existe un OWNER. El comando no crea otro ni modifica cuentas existentes.');

            return self::FAILURE;
        }

        $email = $this->argument('email') ?? $this->ask('Correo del OWNER');
        $name = $this->option('name') ?? $this->ask('Nombre');
        $password = $this->secret('Contraseña (12 caracteres, mayúsculas, minúsculas, números y símbolos)');
        $confirmation = $this->secret('Confirma la contraseña');
        $validator = Validator::make(['email' => $email, 'name' => $name, 'password' => $password, 'password_confirmation' => $confirmation], [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $created = Cache::lock('idevera:provision-owner', 15)->block(5, fn () => DB::transaction(function () use ($name, $email, $password): bool {
            if (User::query()->where('role', Role::OWNER)->exists()) {
                return false;
            }
            $user = new User;
            $user->forceFill(['name' => $name, 'email' => $email, 'password' => $password, 'role' => Role::OWNER, 'email_verified_at' => now()])->save();
            AuditEvent::create(['actor_id' => null, 'target_user_id' => $user->id, 'event' => 'account.owner_provisioned', 'metadata' => ['role' => Role::OWNER->value, 'source' => 'local_console']]);

            return true;
        }));
        if (! $created) {
            $this->error('Ya existe un OWNER.');

            return self::FAILURE;
        }
        $this->info('OWNER creado. Inicia sesión y activa y confirma dos factores en Ajustes > Seguridad para acceder a /owner.');

        return self::SUCCESS;
    }
}
