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
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('payee_id')->nullable();
            $table->unsignedInteger('recurring_id')->default(0);
            $table->unsignedInteger('category_id');
            $table->integer('invoice_no');
            $table->string('invoice_number');
            $table->timestamp('invoice_date')->nullable();
            $table->timestamp('invoice_due_date')->nullable();
            $table->tinyInteger('invoice_status')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->text('overdue_reason')->nullable();
            $table->string('currency');
            $table->float('sub_total')->nullable();
            $table->float('tax')->nullable();
            $table->float('discount')->nullable();
            $table->float('bonus')->nullable();
            $table->float('total')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->text('invoice_item_headings')->nullable();
            $table->string('qrcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
