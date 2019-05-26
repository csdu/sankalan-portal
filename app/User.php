<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are NOT mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * All teams belonging to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Individual Team of which user is only member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function individualTeam()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Is user an Admin?
     *
     * @return bool
     */
    public function isAdmin()
    {
        return ! ! $this->is_admin;
    }

    /**
     * Create and return team for user with the given name and member.
     *
     * @param string $name Team's name.
     * @param \App\User|null $member (optional) Other member of team.
     * @return \App\Team Created Team
     */
    public function createTeam($name, $member = null)
    {
        // Create a team for user
        $team = $this->teams()->create([
            'name' => $name,
        ]);

        if ($member) {
            // attach a memeber if given
            $team->members()->attach($member);
            return $team;
        }

        // Associate individual Team.
        $this->individualTeam()->associate($team);
        $this->save();

        return $team;
    }

    /**
     * UID accessor method to add UID PREFIX `SNKLN-U-{id}`.
     *
     * @return string UID
     */
    public function getUidAttribute()
    {
        return env('ID_PREFIX', 'SNKLN') . '-U' . str_pad("$this->id", 3, '0', STR_PAD_LEFT);
    }

    /**
     * Name accessor method to access full name.
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * MD5 Hash email accessor to access email hash.
     *
     * @return string MD5 Hashed Email
     */
    public function getEmailHashAttribute()
    {
        return md5(strtolower(trim($this->email)));
    }
}
