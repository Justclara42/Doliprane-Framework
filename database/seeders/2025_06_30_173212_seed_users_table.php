<?php

use Faker\Factory;
use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function run() {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Capsule::table('users')->insert([
                'username' => $faker->sentence(3),
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'email' => $faker->sentence(3)
            ]);
        }
    }
};
