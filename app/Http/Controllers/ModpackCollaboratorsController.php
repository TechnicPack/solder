<?php

/*
 * This file is part of Solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

use App\Modpack;

class ModpackCollaboratorsController extends Controller
{
    public function store(Modpack $modpack)
    {
        $this->authorize('update', $modpack);

        request()->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $modpack->addCollaborator(request('user_id'));

        return redirect('modpacks/'.$modpackSlug);
    }
}
