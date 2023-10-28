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
        Schema::create('parlour_has_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("parlour_list_id");
            $table->string("service_name");
            $table->decimal("price",28,8);
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
        Schema::dropIfExists('parlour_has_services');
    }
};
