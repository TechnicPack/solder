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

use App\Collaborator;

class CollaboratorsController extends Controller
{
    public function destroy(Collaborator $collaborator)
    {
        $this->authorize('update', $collaborator->modpack);

        $collaborator->delete();

        return redirect()->route('modpacks.show', $collaborator->modpack);
    }
}
