<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag');
        });

        // Create the application roles
        $roles = collect([
            ['tag' => 'manage-keys'],
            ['tag' => 'manage-users'],
            ['tag' => 'manage-clients'],
            ['tag' => 'create-modpack'],
            ['tag' => 'update-modpack'],
            ['tag' => 'delete-modpack'],
            ['tag' => 'create-package'],
            ['tag' => 'update-package'],
            ['tag' => 'delete-package'],
        ]);

        $roles->each(function ($role) {
            DB::table('roles')->insert($role);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
