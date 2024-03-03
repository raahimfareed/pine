<?php

use Illuminate\Database\Capsule\Manager as Capsule;

return new class
{
  public function up(): void
  {
    Capsule::schema()->create('users', function ($table) {
      $table->id();
      $table->string('email')->unique();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Capsule::schema()->drop('users');
  }
};
