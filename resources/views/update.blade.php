@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header text-white font-size-18px font-weight-bold text-uppercase red-stripes-bg">Update the server statistics</div>

                    <div class="card-body">

                        {{--// The user is authenticated...--}}
                        <h3>Press the button and update the database :)</h3>
                        <form method="get" action="/update/do-update">
                            {{csrf_field()}}

                            <div class="form-group margin-top-30px">
                                <button type="submit" class="btn interface-red-bg text-white">Update</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
