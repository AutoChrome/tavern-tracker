<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuildwarMatchTrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guildwar_match_track', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guild_id');
            $table->unsignedBigInteger('opp_guild_id');
            $table->string('opp_guild_name');
            $table->date('battle_date')->nullable();
            $table->integer('time_of_battle')->nullable();
            $table->integer('log_type');
            $table->integer('win_lose')->nullable();

            $table->foreign('guild_id')->references('guilds')->on('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guildwar_match_track');
    }
}
