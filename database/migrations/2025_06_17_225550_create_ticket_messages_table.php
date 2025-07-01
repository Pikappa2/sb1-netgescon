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
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id(); // Chiave primaria
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Ticket a cui appartiene il messaggio
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Utente che ha scritto il messaggio (se loggato)
            $table->text('messaggio'); // Contenuto del messaggio
            $table->timestamps(); // Include created_at (data invio)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
