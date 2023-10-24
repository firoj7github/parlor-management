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
        Schema::create('support_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("support_ticket_id");
            $table->unsignedBigInteger("sender");
            $table->string("sender_type");
            $table->unsignedBigInteger("receiver")->nullable();
            $table->string("receiver_type")->nullable();
            $table->text("message");
            $table->boolean("seen")->default(false);
            $table->timestamps();

            $table->foreign("support_ticket_id")->references("id")->on("support_tickets")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_chats');
    }
};
