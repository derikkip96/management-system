<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leave_application_id')->unsigned();
            $table->foreign('leave_application_id')->references('id')->on('leave_application')->onDelete('cascade');
            $table->longText('reliever');
            $table->longText('hod')->nullable();
            $table->longText('hr')->nullable();
            $table->longText('md')->nullable();
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
        Schema::dropIfExists('reasons');
    }
}
