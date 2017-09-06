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
                    <br>
                    <p>This site is in Alpha, expect some bugs unfortunately.</p>
                    <p>If you have any files you wish to have shared on here, please email <a href="mailto:resources@bmwf3x.uk">resources@bmwf3x.uk</a>, please also email me if you find any bugs.</p>
                    <br>
                    <br>
                    <p><strong>NOTE:</strong> We wont be hosting any software links here directly, we will have offsite links for software like eSys and ISTA etc.</p>

                    @if (!app()->environment("production"))
                        <br>
                        <pre><code>{{ json_encode(auth()->user(), JSON_PRETTY_PRINT) }}</code></pre>
                        <br>
                        <pre><code>{{ json_encode(auth()->user()->member, JSON_PRETTY_PRINT) }}</code></pre>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
