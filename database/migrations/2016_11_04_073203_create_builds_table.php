<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('modpack_id');
            $table->string('version');
            $table->boolean('published')->default(false);
            $table->text('tags');
            $table->timestamps();

            $table->primary('id');
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
        Schema::dropIfExists('builds');
    }
}
