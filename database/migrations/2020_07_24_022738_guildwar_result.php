<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuildwarResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guildwar_result', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('wizard_id');
            $table->string('opp_wizard_name');
            $table->integer('win_count');
            $table->integer('draw_count');
            $table->integer('lose_count');
            $table->datetime('battle_end', 0);
            $table->integer('log_type');

            $table->foreign('match_id')->references('guildwar_match_track')->on('id');
            $table->foreign('wizard_id')->references('guild_members')->on('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guildwar_result');
    }
}
