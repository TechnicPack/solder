<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers\Settings;

use App\Team;
use App\Http\Controllers\Controller;

class TeamNameController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Team $team)
    {
        $this->validate(request(), [
            'name' => ['required'],
        ]);

        $team->update([
            'name' => request('name'),
        ]);

        return response()->json(['data' => $team]);
    }
}
