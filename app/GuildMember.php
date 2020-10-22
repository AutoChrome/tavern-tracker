<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'id', 'guild_id', 'wizard_name', 'war_status',
	];

	public function guild(){
		return $this->belongsto(App\Guild::class);
	}

	public function results()
	{
		return $this->hasMany(GuildWarResult::class, 'wizard_id'); 
	}

	public function siegeResults()
	{
		return $this->hasMany(GuildSiegeResult::class, 'wizard_id');
	}
}
