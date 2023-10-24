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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('Money in/deposit/add, withdrawal');
            $table->string('trx_id')->comment('Transaction ID');
            $table->decimal('amount', 28, 8)->nullable();
            $table->decimal('percent_charge', 28, 8)->nullable();
            $table->decimal('fixed_charge', 28, 8)->nullable();
            $table->decimal('total_charge', 28, 8)->nullable();
            $table->decimal('total_payable', 28, 8)->nullable();
            $table->decimal('available_balance', 28, 8);
            $table->string('currency_code');
            $table->string('charge_status', 20)->nullable()->comment('Charge added == +, minus == -');
            $table->string('remark')->nullable();
            $table->string('details')->nullable();
            $table->tinyInteger('status')->nullable();

            $table->unsignedBigInteger('user_wallet_id')->nullable();
            $table->foreign("user_wallet_id")->references("id")->on("user_wallets")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('payment_gateway_id')->nullable();
            $table->foreign("payment_gateway_id")->references("id")->on("payment_gateways")->onDelete("cascade")->onUpdate("cascade");
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
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
