<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            $table->foreignId('super_admin_id')->constrained('users')->onDelete('cascade');
            $table->string('code', 6); // 6 haneli kod
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->enum('status', ['pending', 'used', 'expired'])->default('pending');
            $table->string('created_ip', 45)->nullable(); // IPv6 desteği için
            $table->timestamps();
            
            // İndeksler
            $table->index(['hospital_id', 'status']);
            $table->index(['code', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_codes');
    }
}
