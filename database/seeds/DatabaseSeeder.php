<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SponsorTableSeeder::class);
        $this->call(UserStatusTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(InstituteTableSeeder::class);

        // Only seed for development purpose only
        if(app()->environment('local')) {
            $this->call(UserTableSeeder::class);
        }

        Model::reguard();
    }
}
