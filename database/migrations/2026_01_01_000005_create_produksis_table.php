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
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_produksi')->index();
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('restrict');
            $table->foreignId('mesin_id')->constrained('mesins')->onDelete('restrict');
            $table->foreignId('part_id')->constrained('parts')->onDelete('restrict');
            $table->foreignId('operator_id')->constrained('users')->onDelete('restrict');
            $table->integer('target_qty');
            $table->integer('good_qty')->default(0);
            $table->integer('total_ng_qty')->default(0);
            $table->string('status')->default('draft')->index();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};
