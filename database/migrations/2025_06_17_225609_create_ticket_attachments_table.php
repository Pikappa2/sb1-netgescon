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
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            // Un allegato può essere legato direttamente al ticket o a un suo aggiornamento
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('ticket_update_id')->nullable()->constrained('ticket_updates')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Utente che ha caricato l allegato')->constrained('users')->onDelete('cascade');

            $table->string('file_path');
            $table->string('original_file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable()->comment('Dimensione in bytes');
            $table->string('description')->nullable(); // Descrizione opzionale dell'allegato
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
