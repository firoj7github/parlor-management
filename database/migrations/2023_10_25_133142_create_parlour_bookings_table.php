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
        Schema::create('parlour_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("parlour_id");
            $table->unsignedBigInteger("schedule_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("payment_gateway_currency_id")->nullable();
            $table->string('date')->comment("Booking Date");
            $table->string('payment_method')->nullable();
            $table->string('slug');
            $table->decimal('total_charge',28,8)->default(0);
            $table->decimal('price',28,8)->default(0);
            $table->decimal('payable_price',28,8)->default(0);
            $table->decimal('gateway_payable_price',28,8)->nullable();
            $table->string('service')->comment('Service Type');
            $table->text('message')->nullable();
            $table->integer('serial_number');
            $table->boolean("status")->default(false)->comment("Booking Status");
            $table->timestamps();

            $table->foreign("parlour_id")->references("id")->on("parlour_lists")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("schedule_id")->references("id")->on("parlour_list_has_schedules")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("payment_gateway_currency_id")->references("id")->on("payment_gateway_currencies")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parlour_bookings');
    }
};
