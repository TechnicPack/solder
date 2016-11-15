<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientModpackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_modpack', function (Blueprint $table) {
            $table->uuid('client_id');
            $table->uuid('modpack_id');

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('modpack_id')->references('id')->on('modpacks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_modpack');
    }
}
