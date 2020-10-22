<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Guild;
use App\GuildMember;
use App\GuildWarResult;

class ExportController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $guild = (array) $request->all();
        $guild_info = $guild['guild']['guild_info'];
        $guild_members = $guild['guild']['guild_members'];
        // // //Check if guild already exists
        $guilds = Guild::all();
        // // //If it does not, create guild with ID.
        if(!Guild::where('id', '=', $guild_info['guild_id'])->exists()){
            if(Guild::create(["id" => $guild_info['guild_id'], 'name' => $guild_info['name']])){
                echo "Guild created!";
            }else{
                echo "Error!";
            }
        }
        // // //Check if member is within guild.
        $current_members = Guild::find($guild_info['guild_id'])->members;
        $idList = array();
        foreach($guild_members as $member){
            if(!GuildMember::Where('id', '=', $member['wizard_id'])->exists()){
                GuildMember::create(['id' => $member['wizard_id'], 'guild_id' => $guild_info['guild_id'], 'wizard_name' => $member['wizard_name'], 'war_status' => 0]);
                echo $member['wizard_name'] . " added to " . $guild_info['name'] . "! <br>";
            }else{
                echo $member['wizard_name'] . " already in guild. <br>";
            }
            array_push($idList, $member['wizard_id']);
        }
        $oldMembers = Guild::find($guild_info['guild_id'])->members->whereNotIn('id', $idList);
        
        if($oldMembers->count() > 0){
            foreach($oldMembers as $member){
                $deleteResults = $member->results;
                $resultId = array();
                foreach($deleteResults as $result){
                    array_push($resultId, $result->id);
                }

                if(GuildWarResult::whereIn('id', $resultId)->delete()){
                    echo "Deleted " . count($resultId) . " guild war results from database.";
                }
                if($member->delete()){
                    echo $member->wizard_name . " was removed from the guild.";
                }
            }
        }
        return response(200);
        //
    }

    public function storeGuildSiegeMatch(Request $request){
        $sieges = (array) $request->all()['guildsiege_match_log_list'];
        foreach($sieges as $siege){
            $guild_id = $siege['guild_id'];
            print_r($siege);
        }
    }
}
