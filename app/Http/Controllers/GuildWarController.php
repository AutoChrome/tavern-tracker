<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GuildMember;
use App\GuildWarResult;
use App\GuildWarTrack;
use DB;

class GuildWarController extends Controller
{
    public function index($param1 = null, $param2 = null)
    {
        //Check if user wants results between date
        if(isset($_GET['range'])){
            //Get last week results
            if($_GET['range'] == 'last'){
                $temp = $this->resultsLastWeek();

                $guildwars = $temp[0];
                $guildwarResults = $temp[1];
            }
        }
        //Get results from range
        else if(isset($_GET['from']) && isset($_GET['to'])){
            $temp = $this->resultsFromRange($_GET['from'], $_GET['to']);

            $guildwars = $temp[0];
            $guildwarResults = $temp[1];
        }
        //Get all results
        else{
            $guildwars = GuildWarTrack::where('guild_id', auth()->user()->guild_id)->get();
            $guildwarResults = DB::table('guildwar_match_track')->join('guildwar_result', 'guildwar_result.match_id', '=', 'guildwar_match_track.id')->join('guild_members', 'guild_members.id', '=', 'guildwar_result.wizard_id')->where('war_status', 1)->get()->groupBy('wizard_id');
        }
        
        //Calculate the total swords members could have used
        $total_swords = ($guildwars->where('log_type', 1)->count() * 3);

        //Get guild war results
        $memberResults = $this->getMemberResults($guildwarResults, $total_swords);

        $statistics = $memberResults[0];
        $defenseStatistics = $memberResults[1];

        $total_wars = $guildwars->where('log_type', 1)->count();
        if($total_wars > 0){
            $guildwar_win_count = $guildwars->where('win_lose', 1)->count();
            $guildwar_win_rate = number_format((($guildwar_win_count / $total_wars) * 100), 2);
        }else{
            $guildwar_win_rate = null;
        }

        if(isset($_GET['sort'])){
            if(strpos($_GET['sort'], '-')){
                usort($statistics, function($a, $b){
                    return $b[str_replace('-', '', $_GET['sort'])] <=> $a[str_replace('-', '', $_GET['sort'])];
                });
            }else{
                usort($statistics, function($a, $b){
                    return $a[$_GET['sort']] <=> $b[$_GET['sort']];
                });
            }
        }

        return view('guildwar_statistic', ['guildwars' => $guildwars->where('log_type', 1)->sortByDesc('battle_date'), 'guildwar_win_rate' => $guildwar_win_rate, 'guildwar_count' => $total_wars, 'total_swords' => $total_swords, 'statistics' => $statistics, 'defenseStatistics' => $defenseStatistics]);
    }

    public function store(Request $request)
    {
        $results = (array) $request->all();
        
        $guildwar = $results['battle_log_list'];

        foreach($guildwar as $war)
        {
        //Check if match id already exists
            if(!GuildWarTrack::where('id', '=', $war['match_id'])->exists()){
                if(GuildWarTrack::create(['id' => $war['match_id'], 'guild_id' => $war['guild_id'], 'opp_guild_id' => $war['opp_guild_id'], 'opp_guild_name' => $war['opp_guild_name'],'battle_date' => date('Y-m-d', $war['battle_end']), 'time_of_battle' => $war['battle_time'], 'log_type' => $war['log_type']])){
                    echo "record created.";
                }
            }

        //Check if result has already been stored
            if(!GuildWarResult::where('id', '=', $war['rid'])->exists()){
                //Check if member is within guild
                if(GuildMember::find($war['wizard_id'])->exists()){
                    $timeofbattle = date("Y-m-d H:i:s", $war['battle_end']);
                    //Insert if result not stored
                    if(GuildWarResult::create(
                        ['id' => $war['rid'], 'match_id' => $war['match_id'], 'wizard_id' => $war['wizard_id'],'opp_wizard_name' => $war['opp_wizard_name'], 'win_count' => $war['win_count'], 'draw_count' => $war['draw_count'], 'lose_count' => $war['lose_count'], 'battle_end' => $timeofbattle, 'log_type' => $war['log_type'], 'win_lose' => null]))
                    {
                        echo "Guild war from: " . $war['wizard_name'] . " added.<br>";
                    }
                }
            }
        }
    }

    public function storeMatch(Request $request)
    {
        $results = (array) $request->all();
        $matches = $results['match_log'];
        foreach($matches as $match){
            //Check if match already exists.
            $updateDetails = array(
                'battle_date' => date('Y-m-d', $match['match_end']),
                'time_of_battle' => $match['match_time'],
                'win_lose' => $match['win_lose']
            );
            if($match = GuildWarTrack::where([['opp_guild_id', '=', $match['opp_guild_id']], ['time_of_battle', '!=', $match['match_time']]])->update($updateDetails)){
                echo "Guild war time updated.";
            }
            //If match does exist, update record with time

            //If not, create record.
        }
    }

    public function getMemberResults($guildwarResults, $total_swords){
        $statistics = array();
        $defenseStatistics = array();
        foreach($guildwarResults as $result){
            $wizard_name = null;
            $wizard_id = null;
            $win_count = 0;
            $defenseWin_count = 0;
            $draw_count = 0;
            $defenseDraw_count = 0;
            $lose_count = 0;
            $defenseLose_count = 0;
            $swords_used = 0;
            $defense_used = 0;
            foreach($result as $match){
                if($match->log_type == 1){
                    if($wizard_name == null){
                        $wizard_name = $match->wizard_name;
                    }
                    if($wizard_id == null){
                        $wizard_id = $match->wizard_id;
                    }
                    $swords_used++;
                    if($match->win_count == 2){
                        $win_count ++;
                    }else if($match->lose_count == 2){
                        $lose_count ++;
                    }else if($match->win_count == 1 && $match->draw_count == 1){
                        $win_count++;
                    }else{
                        $draw_count ++;
                    }
                }else{
                    $defense_used++;
                    if($match->win_count == 2){
                        $defenseWin_count++;
                    }else if($match->lose_count == 2){
                        $defenseLose_count++;
                    }else if($match->win_count == 1 && $match->draw_count == 1){
                        $defenseWin_count++;
                    }else{
                        $defenseDraw_count++;
                    }
                }
            }

            $swords_missed = $total_swords - $swords_used;

            if($defense_used > 0){
                $defenseWin_rate = number_format(($defenseWin_count / $defense_used) * 100, 2);
                $defenseLose_rate = number_format(($defenseLose_count / $defense_used) * 100, 2);
                $defenseDraw_rate = number_format(($defenseDraw_count / $defense_used) * 100, 2);
            }else{
                $defenseWin_rate = 0;
                $defenseLose_rate = 0;
                $defenseDraw_rate = 0;
            }

            if($wizard_name != null){
                array_push($statistics, 
                    array(
                        'wizard_name' => $wizard_name, 
                        'wizard_id' => $wizard_id, 
                        'win_count' => $win_count, 
                        'missed_rate' => number_format(($swords_missed / $total_swords * 100), 2), 
                        'win_rate' => number_format(($win_count / $swords_used * 100), 2),
                        'draw_count' => $draw_count, 
                        'draw_rate' => number_format(($draw_count / $swords_used) * 100, 2), 
                        'lose_count' => $lose_count, 
                        'lose_rate' => number_format(($lose_count / $swords_used * 100), 2), 
                        'swords_used' => $swords_used, 'swords_missed' => $swords_missed
                    )
                );

                array_push($defenseStatistics, 
                    array(
                        'wizard_name' => $wizard_name, 
                        'wizard_id' => $wizard_id, 
                        'win_count' => $defenseWin_count, 
                        'draw_count' => $defenseDraw_count, 
                        'lose_count' => $defenseLose_count, 
                        'defense_used_count' => $defense_used, 
                        'win_rate' => $defenseWin_rate, 
                        'lose_rate' => $defenseLose_rate, 
                        'draw_rate' => $defenseDraw_rate
                    )
                );
            }

            $wizard_name = null;
            $wizard_id = null;
        }

        return [$statistics, $defenseStatistics];
    }

    public function resultsLastWeek(){
        $lastsunday = date('Y-m-d',strtotime('last sunday'));
        $lastlastsunday = date('Y-m-d',strtotime('last sunday -7 days'));

        $guildwars = GuildWarTrack::where('guild_id', auth()->user()->guild_id)->whereBetween('battle_date', [$lastlastsunday, $lastsunday])->get();
        $guildwarResults = DB::table('guildwar_match_track')->join('guildwar_result', 'guildwar_result.match_id', '=', 'guildwar_match_track.id')->join('guild_members', 'guild_members.id', '=', 'guildwar_result.wizard_id')->where('war_status', 1)->whereBetween('battle_date', [$lastlastsunday, $lastsunday])->get()->groupBy('wizard_id');

        return [$guildwars, $guildwarResults];
    }

    public function resultsFromRange($from, $to){
        $from = date('Y-m-d',strtotime($from));
        $to = date('Y-m-d',strtotime($to));

        $guildwars = GuildWarTrack::where('guild_id', auth()->user()->guild_id)->whereBetween('battle_date', [$from, $to])->get();
        $guildwarResults = DB::table('guildwar_match_track')->join('guildwar_result', 'guildwar_result.match_id', '=', 'guildwar_match_track.id')->join('guild_members', 'guild_members.id', '=', 'guildwar_result.wizard_id')->where('war_status', 1)->whereBetween('battle_date', [$from, $to])->get()->groupBy('wizard_id');

        return [$guildwars, $guildwarResults];
    }

    // public function createSort($sorts){
    //     foreach($sorts as $sort){
    //           request()->query->add(['sort' => $sort['sort'], 'order' => 'asc']);
    //           route('guildwar-statistic') . '?' . http_build_query(request()->query->all());
    //     }
    // }
}
