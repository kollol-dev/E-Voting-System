<?php

use Illuminate\Database\Seeder;
class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('election_policies')->insert([
            'policy' => ''
        ]);
    }
}
