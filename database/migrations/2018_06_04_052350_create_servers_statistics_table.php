<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('servers_statistics_server_id')->references('id')->on('servers');
            $table->integer('servers_statistics_value');
            $table->timestamp('servers_statistics_date');
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
        Schema::dropIfExists('servers_statistics');
    }
}
