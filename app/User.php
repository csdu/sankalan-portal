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

    protected static function boot() {
        parent::boot();

        static::created(function($user){
            $user->update(['uid' => env("ID_PREFIX", "SNKLN") . "-U" . str_pad("$user->id", 3, "0", STR_PAD_LEFT)]);
        });
    }

    public function teams() {
        return $this->belongsToMany(Team::class);
    }

    public function individualTeam() {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function createTeam($name, $member_id = null) {
        $team = $this->teams()->create(compact('name'));
        
        if($member_id) {
            $team->members()->attach($member_id);
            return $team;
        } 
        
        $this->individualTeam()->associate($team);
        $this->save();

        return $team;
    }
}
