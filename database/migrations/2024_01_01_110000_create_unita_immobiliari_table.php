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
        Schema::create('unita_immobiliari', function (Blueprint $table) {
            $table->id('id_unita');
            $table->unsignedBigInteger('id_stabile');
            $table->string('fabbricato')->nullable();
            $table->string('interno')->nullable();
            $table->string('scala')->nullable();
            $table->string('piano')->nullable();
            $table->string('subalterno')->nullable();
            $table->string('categoria_catastale', 10)->nullable();
            $table->decimal('superficie', 8, 2)->nullable();
            $table->decimal('vani', 5, 2)->nullable();
            $table->string('indirizzo')->nullable()->comment('Indirizzo specifico se diverso da quello del condominio');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unita_immobiliari');
    }
};