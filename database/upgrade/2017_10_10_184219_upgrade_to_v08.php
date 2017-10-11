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

        Schema::rename('mods', 'packages');

        Schema::table('packages', function (Blueprint $table) {
            $table->renameColumn('name', 'slug');
            $table->renameColumn('pretty_name', 'name');
        });

        DB::update("update releases inner join packages on releases.package_id = packages.id set path = concat('mods/', packages.slug, '/', packages.slug, '-', releases.version, '.zip')");

        Schema::rename('build_modversion', 'build_release');

        Schema::table('build_release', function (Blueprint $table) {
            $table->renameColumn('modversion_id', 'release_id');
        });

        Schema::table('keys', function (Blueprint $table) {
            $table->renameColumn('api_key', 'token');
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
