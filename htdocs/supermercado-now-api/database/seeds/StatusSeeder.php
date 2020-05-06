<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            ['name' => 'Pendente', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Em análise', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Aprovado', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Reprovado', 'created_at' => date('Y-m-d H:i:s')],
        ]);

        $this->command->info('Todos os status foram criados.');
    }
}
