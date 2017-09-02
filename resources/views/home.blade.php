@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include("components.sidebar")
        </div>

        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Resources</div>

                <div class="panel-body">
                    <p>You are logged in!</p>
                    <br>
                    <p>Is a Member?? {{ auth()->user()->is_member ? "YES" : "NO" }}</p>
                    <br>
                    <pre><code>{{ json_encode(auth()->user(), JSON_PRETTY_PRINT) }}</code></pre>
                    <br>
                    <pre><code>{{ json_encode(auth()->user()->member, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
