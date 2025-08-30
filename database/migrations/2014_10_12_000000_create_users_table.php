<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Role enum
            $table->enum('role', ['super_admin', 'admin', 'doctor', 'representative', 'patient'])->default('patient');
            
            // Hospital ilişkisi
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->onDelete('set null');
            
            // İletişim bilgileri
            $table->string('phone_country_code', 5)->nullable();
            $table->string('phone')->nullable();
            
            // Hasta atamaları
            $table->foreignId('assigned_doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_representative_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Durum ve son giriş
            $table->enum('status', ['pending', 'active', 'suspended'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            // İndeksler
            $table->index(['hospital_id', 'role']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
