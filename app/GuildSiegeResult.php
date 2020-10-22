<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildSiegeResult extends Model
{
	public function guildMember()
	{
		return $this->belongsTo(GuildMember::class, 'wizard_id');
	}
}
