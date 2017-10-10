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

class UpdateModpacksTableAddImageLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modpacks', function ($table) {
            $table->string('icon_url')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('background_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modpacks', function ($table) {
            $table->dropColumn(['icon_url', 'logo_url', 'background_url']);
        });
    }
}
