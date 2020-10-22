@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card @if(Auth::user()->dark_mode) dark-mode @endif">
                <div class="card-header bg-primary text-white">
                    asdf2
                </div>
                <div class="card-body @if(Auth::user()->dark_mode) text-white @endif">
                    asdf
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card @if(Auth::user()->dark_mode) dark-mode @endif">
                <div class="card-header bg-primary text-white">
                    asdf2
                </div>
                <div class="card-body @if(Auth::user()->dark_mode) text-white @endif">
                    asdf
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card @if(Auth::user()->dark_mode) dark-mode @endif">
                <div class="card-header bg-primary text-white">
                    Guild battle history</i>
                </div>
                <div class="card-body text-white">
                    <table class="table @if(Auth::user()->dark_mode) text-white @endif">
                        <thead>
                            <tr>
                                <th class="text-center">Battle</th>
                                <th class="text-center">Start</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guildwars as $war)
                            <tr>
                                <td><b>{{$war->opp_guild_name}}</b></td>
                                <td class="text-right">{{date('d-m-Y', strtotime($war->battle_date))}} <button class="btn btn-primary ml-4"><i class="fa fa-search" aria-hidden="true"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
