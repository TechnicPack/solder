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

class CreateUsersTable extends Migration
{
    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('created_ip');
            $table->string('last_ip')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'created_ip' => gethostbyname(gethostname()),
        ]);
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
