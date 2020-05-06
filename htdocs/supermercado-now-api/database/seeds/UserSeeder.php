<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrCreate([
            'name' => 'Administrador',
            'email' => 'admin@mail.com',
        ], ['password' => bcrypt('123456')]);

        $defaultUser = User::firstOrCreate([
            'name' => 'Usuário padrão',
            'email' => 'user@mail.com',
        ], ['password' => bcrypt('123456')]);

        $admin->assignRole('admin');
        $defaultUser->assignRole('defaultUser');
        $this->command->info('1 usuário administrador e 1 usuário padrão foram criados.');
    }
}
