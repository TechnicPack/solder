<?php

/*
 * This file is part of TechnicPack Solder.
 *
 * (c) Syndicate LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

/**
 * Trait CanJoinTeams.
 *
 * @property \Illuminate\Database\Eloquent\Collection teams
 * @property int current_team_id
 */
trait CanJoinTeams
{
    /**
     * All teams related to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get the current team as an attribute.
     *
     * @return Team
     */
    public function getCurrentTeamAttribute()
    {
        return $this->currentTeam();
    }

    /**
     * get the users current team.
     *
     * @return \Illuminate\Database\Eloquent\Model|Team
     */
    public function currentTeam()
    {
        if (is_null($this->current_team_id)) {
            $this->switchToTeam($this->teams->first());

            return $this->currentTeam();
        }

        return $this->teams->find($this->current_team_id);
    }

    /**
     * Switch the users current team to the specified team.
     *
     * @param $team
     */
    public function switchToTeam($team)
    {
        $this->current_team_id = $team->id;
        $this->save();
    }

    /**
     * Determine if the user has any teams.
     *
     * @return bool
     */
    public function hasTeams()
    {
        return count($this->teams) > 0;
    }

    /**
     * Determine if the user is on the given team.
     *
     * @param $team
     * @return bool
     */
    public function onTeam($team)
    {
        return $this->teams->contains($team);
    }
}
