@extends('layouts.app')

<style>
    tr th{
        position: sticky; 
        top: 0; 
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        background-color:#f8fafc;
    }

    tr.dark-mode th{
        position: sticky; 
        top: 0; 
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        background-color:#343a40;
    }
</style>

@section('content')
<div class="container">
    <div class="row mb-2 h-15">
        <div class="col-md-6">
            <div class="card @if(Auth::user()->dark_mode) dark-mode @endif">
                <div class="card-header bg-primary text-white">
                    Guild war overview
                </div>
                <div class="card-body @if(Auth::user()->dark_mode) text-white @endif">
                    <b>@if($guildwar_win_rate != null) {{$guildwar_win_rate}} @endif % win rate</b>
                    <br>
                    <b>@if($guildwar_count != null) {{$guildwar_count}} @endif wars done</b>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card @if(Auth::user()->dark_mode) dark-mode @endif">
                <div class="card-header bg-primary text-white">
                    Battles
                </div>
                <div class="card-body @if(Auth::user()->dark_mode) text-white @endif">
                    <div class="table-responsive" style="max-height:200px;">
                        <table class="table @if(Auth::user()->dark_mode) table-dark @endif">
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Result</td>
                                    <td>Date</td>
                                    <td>View</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guildwars as $war)
                                <tr>
                                    <td>{{$war->opp_guild_name}}</td>
                                    @if($war->win_lose == 1)
                                    <td>Won</td>
                                    @elseif($war->win_lose == 2)
                                    <td>Lost</td>
                                    @else
                                    <td>TBD</td>
                                    @endif
                                    <td>{{date('d-m-Y', strtotime($war->battle_date))}}</td>
                                    <td><button class="btn btn-primary"><span><i class="fas fa-search"></i></span></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <ul class="nav nav-tabs @if(Auth::user()->dark_mode) dark-mode @endif" style="border-radius:5px 5px 0px 0px;">
              <li class="nav-item"><a class="nav-link @if(Request()->range == null) active @endif" href="{{route('guildwar-statistic')}}">Summary</a></li>
              <li class="nav-item"><a class="nav-link @if(Request()->range == 'last') active @endif" href="{{route('guildwar-statistic') . '?' . http_build_query(array('range' => 'last'))}}">Last week</a></li>
              <li class="nav-item"><a class="nav-link" href="#">2 Weeks</a></li>
          </ul>
          <div class="table-responsive"  style="max-height:500px;">
            <table class="table @if(Auth::user()->dark_mode) table-dark @endif table-bordered table-hover table-sm">
                <thead>
                    <tr class="@if(Auth::user()->dark_mode) dark-mode @endif">
                        <th>Member <span class="float-right"><i class="fa fa-sort"></i></span></th>
                        <th><a href="#">Swords used <span><i class="fa fa-sort text-white float-right"></i></span></a></th>
                        <th><a href="#">Swords missed <span><i class="fa fa-sort text-white float-right"></i></span></a></th>
                        <th>Missed rate <span><i class="fa fa-sort text-white float-right"></i></span></th>
                        <th>Win rate <span><i class="fa fa-sort text-white float-right"></i></span></th>
                        <th>Draw rate <span><i class="fa fa-sort text-white float-right"></i></span></th>
                        <th>Lose rate <span><i class="fa fa-sort text-white float-right"></i></span></th>
                        <th>Win count <span><i class="fa fa-sort text-white float-right"></i></span></th>
                        <th>Draw count <span><i class="fa fa-sort text-white float-right"></i></span></th>
                        <th>Lose count <span><i class="fa fa-sort text-white float-right"></i></span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistics as $member)
                    <tr>
                        <td>{{$member['wizard_name']}}</td>
                        <td>{{$member['swords_used']}}</td>
                        <td>{{$member['swords_missed']}}</td>
                        <td>{{$member['missed_rate']}}%</td>
                        <td>{{$member['win_rate']}}%</td>
                        <td>{{$member['draw_rate']}}%</td>
                        <td>{{$member['lose_rate']}}%</td>
                        <td>{{$member['win_count']}}</td>
                        <td>{{$member['draw_count']}}</td>
                        <td>{{$member['lose_count']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive"  style="max-height:500px;">
            <table class="table @if(Auth::user()->dark_mode) table-dark @endif table-bordered table-hover table-sm">
                <thead>
                    <tr class="@if(Auth::user()->dark_mode) dark-mode @endif">
                        <th>Member</th>
                        <th>Win rate</th>
                        <th>Draw rate</th>
                        <th>Lose rate</th>
                        <th>Win count</th>
                        <th>Draw count</th>
                        <th>Lose count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($defenseStatistics as $member)
                    <tr>
                        <td>{{$member['wizard_name']}}</td>
                        <td>{{$member['win_rate']}}%</td>
                        <td>{{$member['draw_rate']}}%</td>
                        <td>{{$member['lose_rate']}}%</td>
                        <td>{{$member['win_count']}}</td>
                        <td>{{$member['draw_count']}}</td>
                        <td>{{$member['lose_count']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
