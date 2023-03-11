<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmlUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Nombres, apellidos, cédula, email, país, dirección y celular
        Schema::create('gml_user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('id_number');
            $table->string('email');
            $table->string('country');
            $table->string('address');
            $table->string('phone_number');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('gml_category');
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
        Schema::dropIfExists('gml_user');
    }
}
