<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'id', 'name',
	];

	public function members()
	{
		return $this->hasMany(GuildMember::class); 
	}
}
