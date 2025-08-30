<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191); // Klinik adı
            $table->string('phone_country_code', 5)->nullable(); // Telefon ülke kodu
            $table->string('phone', 20)->nullable(); // Telefon
            $table->string('email', 191)->unique(); // E-posta
            $table->string('tax_number', 50)->nullable(); // Vergi numarası
            $table->string('city', 100); // Şehir
            $table->string('country', 100); // Ülke
            $table->string('website', 191)->nullable(); // Web sitesi
            $table->text('address'); // Adres (uzun)
            $table->text('description')->nullable(); // Açıklama
            $table->text('notes')->nullable(); // Notlar
            $table->string('logo_path')->nullable(); // Logo dosya yolu
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending'); // Durum
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Oluşturan kullanıcı
            $table->timestamps();
            $table->softDeletes(); // Soft delete

            // İndeksler
            $table->index('name', 'hospitals_name_index');
            $table->index(['city', 'country'], 'hospitals_city_country_index');
            $table->index('status', 'hospitals_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
