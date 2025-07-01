<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proprieta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unita_immobiliare_id')->constrained('unita_immobiliari', 'id_unita')->onDelete('cascade');
            $table->foreignId('soggetto_id')->constrained('soggetti', 'id_soggetto')->onDelete('cascade');
            $table->enum('tipo_diritto', ['proprietario', 'nudo_proprietario', 'usufruttuario', 'inquilino', 'comodatario', 'altro']);
            $table->decimal('percentuale_possesso', 5, 2)->nullable();
            $table->date('data_inizio')->nullable();
            $table->date('data_fine')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proprieta');
    }
};
