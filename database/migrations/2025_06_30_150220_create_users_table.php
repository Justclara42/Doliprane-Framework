<?php
use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up() {
        Capsule::schema()->create('users', function ($table) {
            $table->id();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('users');
    }
};
