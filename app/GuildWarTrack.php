<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildWarTrack extends Model
{
	public $timestamps = false;
	protected $table = 'guildwar_match_track';

	protected $fillable = [
		'id', 'guild_id', 'opp_guild_id', 'opp_guild_name', 'battle_date', 'time_of_battle', 'log_type', 'win_lose'
	];

	public function results()
	{
		return $this->hasMany(GuildWarResult::class, 'match_id'); 
	}

	public function offenseResultsByWizardID($id)
	{
		return $this->hasMany(GuildWarResult::class, 'match_id')->where([['wizard_id', '=', $id], ['log_type', '=', 1]])->get(); 
	}

		public function defenseResultsByWizardID($id)
	{
		return $this->hasMany(GuildWarResult::class, 'match_id')->where([['wizard_id', '=', $id], ['log_type', '=', 2]])->get(); 
	}
}
