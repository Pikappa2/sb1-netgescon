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
        Schema::create('conti_condominio', function (Blueprint $table) {
            $table->bigIncrements('id_conto_condominio');
            $table->unsignedBigInteger('id_stabile');
            $table->string('codice_conto', 50)->nullable()->unique();
            $table->string('nome_conto');
            $table->string('iban', 34)->nullable()->unique();
            $table->string('bic_swift', 11)->nullable();
            $table->string('nome_banca')->nullable();
            $table->string('filiale_banca')->nullable();
            $table->string('tipo_conto', 50)->comment('Es. BANCARIO, CASSA, PAYPAL');
            $table->decimal('saldo_iniziale', 15, 2)->default(0.00);
            $table->date('data_saldo_iniziale');
            $table->string('valuta', 3)->default('EUR');
            $table->boolean('attivo')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');

          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conti_condominio');
    }
};
