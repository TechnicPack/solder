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
use App\Collaborator;

class CollaboratorsController extends Controller
{
    public function destroy($collaboratorId)
    {
        $collaborator = Collaborator::find($collaboratorId);
        $modpack = Modpack::find($collaborator->modpack_id);

        $this->authorize('update', $modpack);

        $collaborator->delete();

        return redirect('modpacks/'.$modpack->slug);
    }
}
