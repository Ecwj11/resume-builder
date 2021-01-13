<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    }
}
