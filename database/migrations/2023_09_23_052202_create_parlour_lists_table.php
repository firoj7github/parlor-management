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
        Schema::create('parlour_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("area_id");
            $table->string('slug');
            $table->string('name');
            $table->string('manager_name');
            $table->string('experience');
            $table->string('speciality')->nullable();
            $table->string('contact');
            $table->string('address')->nullable();
            $table->string('off_days');
            $table->integer('number_of_dates');
            $table->string('image')->nullable();
            $table->boolean("status")->default(true);
            $table->timestamps();

            $table->foreign("area_id")->references("id")->on("areas")->onDelete("cascade")->onUpdate("cascade");
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parlour_lists');
    }
};
