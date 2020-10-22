<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildWarResult extends Model
{
	public $timestamps = false;
	protected $table = 'guildwar_result';

	protected $fillable = [
		'id', 'match_id', 'wizard_id','opp_wizard_name', 'win_count', 'draw_count', 'lose_count', 'battle_end', 'log_type',
	];

	public function guildMember()
	{
		return $this->belongsTo(GuildMember::class, 'wizard_id');
	}
}
