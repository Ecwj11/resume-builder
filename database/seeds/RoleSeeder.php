<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$dateNow = Carbon::now();
        DB::table('roles')->insert([
        	['role' => 'admin', 'created_at' => $dateNow],
        	['role' => 'user', 'created_at' => $dateNow]
        ]);

        $model = Role::where(['role' => Role::ROLE_ADMIN])->get()->first();

        DB::table('users')->insert([
            [
                'email' => 'admin@admin.com',
                'password' => hash::make('12345678'),
                'role_id' => $model->id, 
                'created_at' => $dateNow
            ],
        ]);
    }
}
