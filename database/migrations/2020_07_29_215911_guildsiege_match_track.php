<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuildsiegeMatchTrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guildsiege_match_track', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guild_id');
            $table->unsignedBigInteger('opp_guild_id1');
            $table->string('opp_guild_name1');
            $table->unsignedBigInteger('opp_guild_id2');
            $table->string('opp_guild_name2');
            $table->date('battle_date')->nullable();
            $table->integer('placement');
            $table->integer('match_score')->nullable();

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
        Schema::dropIfExists('guildsiege_match_track');
    }
}
