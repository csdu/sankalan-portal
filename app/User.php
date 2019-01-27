<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
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

    public function getUidAttribute()
    {
        return env("ID_PREFIX", "SNKLN") . "-U" . str_pad("$this->id", 3, "0", STR_PAD_LEFT);
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function getEmailHashAttribute()
    {
        return md5(strtolower(trim($this->email)));
    }

    public function teams() {
        return $this->belongsToMany(Team::class);
    }

    public function individualTeam() {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function createTeam($name, $member = null) {
        $team = $this->teams()->create(compact('name'));
        
        if($member) {
            $team->members()->attach($member);
            return $team;
        } 
        
        $this->individualTeam()->associate($team);
        $this->save();

        return $team;
    }
}
