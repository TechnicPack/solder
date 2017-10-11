<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeToV08 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('created_ip');
            $table->dropColumn('last_ip');
            $table->dropColumn('updated_by_ip');
            $table->dropColumn('created_by_user_id');
            $table->dropColumn('updated_by_user_id');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::rename('modversions', 'releases');

        Schema::table('releases', function (Blueprint $table) {
            $table->renameColumn('mod_id', 'package_id');
            $table->string('path');
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        throw new Exception('Please restore your backup to roll back the v0.8 upgrade.');
    }
}
