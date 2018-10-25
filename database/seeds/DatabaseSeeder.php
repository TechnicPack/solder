<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the default admin
        $user = \App\User::create([
            'username' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'is_admin' => true,
            'email_verified_at' => \Carbon\Carbon::now(),

        ]);

        $team = \App\Team::create([
            'name' => 'Default Team',
            'slug' => 'default',
            'owner_id' => $user->id,
        ]);

        $user->teams()->attach($team);
        $user->switchToTeam($team);
    }
}
