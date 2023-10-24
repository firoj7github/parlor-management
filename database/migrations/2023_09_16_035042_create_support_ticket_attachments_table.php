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
        Schema::create('support_ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("support_ticket_id");
            $table->string("attachment",255)->nullable();
            $table->text("attachment_info",1000)->nullable();
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
        Schema::dropIfExists('support_ticket_attachments');
    }
};
