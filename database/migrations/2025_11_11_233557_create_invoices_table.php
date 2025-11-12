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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->enum('type', ['sale', 'expense'])->default('sale');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('vat_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            $table->date('invoice_date');
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->decimal('quantity', 12, 2);
            $table->string('unit_name')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('vat_percent');
            $table->decimal('line_total', 12, 2);

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
    }
};
