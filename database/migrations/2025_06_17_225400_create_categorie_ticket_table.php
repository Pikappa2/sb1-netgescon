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
        Schema::create('categorie_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descrizione')->nullable();
            // Potresti aggiungere foreign key per assegnatari/fornitori di default
            // $table->foreignId('default_user_id')->nullable()->constrained('users')->onDelete('set null');
            // $table->foreignId('default_fornitore_id')->nullable()->constrained('fornitori')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie_ticket');
    }
};
