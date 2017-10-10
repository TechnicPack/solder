<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;

class CreateModpacksTable extends Migration
{
    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modpacks', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('recommended')->nullable();
            $table->string('latest')->nullable();
            $table->string('url')->nullable();
            $table->string('icon_md5')->nullable();
            $table->string('logo_md5')->nullable();
            $table->string('background_md5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('modpacks');
    }
}
