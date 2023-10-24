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
        Schema::create('usefull_links', function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->text("title");
            $table->string("slug");
            $table->string("url");
            $table->longText("content");
            $table->boolean("status")->default(true);
            $table->boolean("editable")->default(false);
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
        Schema::dropIfExists('usefull_links');
    }
};
