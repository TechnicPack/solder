<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModpacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modpacks', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->boolean('published')->default(false);
            $table->uuid('promoted_build_id')->nullable();
            $table->uuid('latest_build_id')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modpacks');
    }
}
