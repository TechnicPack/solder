<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('mod_id');
            $table->string('version');
            $table->timestamps();

            $table->primary('id');
            $table->unique(['mod_id', 'version']);
            $table->foreign('mod_id')->references('id')->on('mods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('releases');
    }
}
