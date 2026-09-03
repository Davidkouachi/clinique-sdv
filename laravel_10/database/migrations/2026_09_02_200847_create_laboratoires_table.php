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
        Schema::create('laboratoires', function (Blueprint $table) {
            $table->id();

            $table->string('patient_id', 20);
            $table->string('code_assurance', 20)->nullable();
            $table->string('code_societe', 20)->nullable();

            $table->string('numfac')->index();

            $table->string('medecin', 150)->nullable();

            $table->decimal('montant_total', 15, 2)->default(0);
            $table->decimal('montant_patient', 15, 2)->default(0);
            $table->decimal('montant_assurance', 15, 2)->default(0);

            $table->decimal('remise', 15, 2)->default(0);
            $table->decimal('taux', 5, 2)->default(0);

            $table->string('periode', 20)->nullable();
            $table->string('numcode', 100)->nullable();
            $table->string('numhosp', 100)->nullable();

            $table->text('rensg')->nullable();

            $table->timestamps();

            $table->index('patient_id');
            $table->index('code_assurance');
            $table->index('code_societe');
        });

        Schema::create('laboratoire_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laboratoire_id')
                ->constrained('laboratoires')
                ->cascadeOnDelete();

            $table->string('examen_id', 50)->nullable();

            $table->string('code', 20);
            $table->string('famille', 20)->nullable();

            $table->string('examen', 255);

            $table->decimal('cotation', 15, 2)->default(0);
            $table->decimal('valeur', 15, 2)->default(0);
            $table->decimal('montant', 15, 2)->default(0);

            $table->decimal('prelevement', 15, 2)->default(0);

            $table->boolean('assurance')->default(false);

            $table->timestamps();

            $table->index('examen_id');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoires');
    }
};
