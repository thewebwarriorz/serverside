@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <h1>Servers list</h1>

                @if(count($servers))
                    <div id="accordion">
                        @foreach($servers as $key => $server)
                            <div class="card">
                                <div class="card-header text-white text-uppercase red-stripes-bg" id="heading-{{$server->id}}">
                                    <h5 class="mb-0">
                                        <button class="btn interface-red-bg text-white font-size-18px font-weight-bold hover" data-toggle="collapse" data-target="#collapse-{{$server->id}}" aria-expanded="true" aria-controls="collapse-{{$server->id}}">
                                            {{$server->server_name}}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse-{{$server->id}}" class="@if ($key === 0) show @endif collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">

                                        <h3>{{$server->server_name}} (requests / minutes) statistic, from the last three hours.</h3>

                                        <div class="table-responsive">
                                            <table class="table">

                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Server ID</th>
                                                    <th scope="col">Minimum</th>
                                                    <th scope="col">Maximum</th>
                                                    <th scope="col">Average</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th scope="row">{{$server->id}}</th>
                                                    <td>{{$serversStatistic[$server->id]["min"]}}</td>
                                                    <td>{{$serversStatistic[$server->id]["max"]}}</td>
                                                    <td>{{$serversStatistic[$server->id]["average"]}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p>
                                            <a href="{{ URL('/servers/statistic/'.$server->id )}}">
                                                <button type="button" class="btn interface-red-bg text-white">Click here the statistic</button>
                                            </a>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">
                        No server statistics, please update the database!
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
