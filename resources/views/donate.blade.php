@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include("components.sidebar")
        </div>

        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Donate</div>

                <div class="panel-body">
                    <p>Hey!</p>
                    <p>I hope you are enjoying the resource site that I have built, and are learning a lot from it.</p>
                    <br>
                    <p>This website costs me a total of £15 per month to run, its a cost I am happy to pay for the community. However, if you are generous enough to help support me, and keep this site alive for the community, then all donations are welcome.</p>
                    <br>
                    <p>If you wish to donate anything, please go here and pay me via PayPal</p>
                    <p><a href="https://www.paypal.me/djekl" target="_blank">https://www.paypal.me/djekl</a></p>
                    <br>
                    <p>I am not begging for money, and I am always grateful for anything you can give to help with the site.</p>
                    <br>
                    <p>Thanks, Alan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
