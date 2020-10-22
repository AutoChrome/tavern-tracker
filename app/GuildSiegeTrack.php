<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildSiegeTrack extends Model
{
	public function results()
	{
		return $this->hasMany(GuildSiegeResult::class, 'match_id'); 
	}
}
