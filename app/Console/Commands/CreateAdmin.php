<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

#[Signature('site:create-admin')]
#[Description('Crear un administrador nuevo de forma interactiva')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('Este comando requiere una terminal interactiva.');

            return self::FAILURE;
        }

        $name = $this->ask('Nombre');
        $email = $this->ask('Correo electrónico');
        $password = $this->secret('Contraseña (mínimo 12 caracteres)');
        $validator = Validator::make(compact('name', 'email', 'password'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            $this->error('Revise el nombre, el correo y la contraseña (mínimo 12 caracteres).');

            return self::FAILURE;
        }

        $email = Str::lower($email);
        if (User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            $this->error('Ya existe un usuario con ese correo; no se modificó.');

            return self::FAILURE;
        }

        $user = new User(['name' => $name, 'email' => $email, 'password' => $password]);
        $user->is_admin = true;
        $user->save();

        $this->info('Administrador creado correctamente.');

        return self::SUCCESS;
    }
}
