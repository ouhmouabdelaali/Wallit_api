<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
// In create_transactions_table migration file
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sender_wallet_id')->constrained('wallets')->onDelete('cascade')->nullable();
    $table->foreignId('receiver_wallet_id')->constrained('wallets')->onDelete('cascade')->nullable();
    $table->decimal('amount', 10, 2);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
