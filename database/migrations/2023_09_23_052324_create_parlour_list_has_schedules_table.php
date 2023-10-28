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
        Schema::create('parlour_list_has_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("parlour_list_id");
            $table->string("from_time");
            $table->string("to_time");
            $table->integer("max_client");
            $table->boolean("status")->default(true);
            $table->timestamps();

            $table->foreign("parlour_list_id")->references("id")->on("parlour_lists")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parlour_list_has_schedules');
    }
};
