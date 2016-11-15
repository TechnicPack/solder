<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildReleaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('build_release', function (Blueprint $table) {
            $table->uuid('build_id');
            $table->uuid('release_id');

            $table->foreign('build_id')->references('id')->on('builds');
            $table->foreign('release_id')->references('id')->on('releases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('build_release');
    }
}
