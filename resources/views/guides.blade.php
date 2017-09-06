@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include("components.sidebar")
        </div>

        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h4>
                        Guides and Other Documents

                        @if (!empty($dir_name))
                            <br>
                            <small>&rdquo;{{ $dir_name }}&ldquo;</small>
                        @endif

                        <br>
                        <br>
                        <small>Some guides/info can lead to problems with cars if done incorrectly.</small>
                        <br>
                        <small>BMW F3x UK takes no responsibilty, and information is provided as is, and to the owners own risk.</small>
                    </h4>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Size</th>
                            <th>Last Modified</th>
                            {{-- <th>Downloads</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($directories as $dir)
                            <tr>
                                <td>
                                    <a href="{{ route("guides.view", [trim("{$pwd}/{$dir}", "/")], false) }}" class="btn btn-block btn-link btn-text text-left-force no-padding">
                                        <i class="fa fa-fw fa-folder" aria-hidden="true"></i>
                                        {{ $dir }}
                                    </a>
                                </td>

                                <td>
                                    n/a
                                </td>

                                <td>
                                    n/a
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($files as $file)
                            <tr>
                                <td>
                                    <a href="{{ route("guides.download", [trim("{$pwd}/{$file["name"]}", "/")], false) }}" class="btn btn-block btn-link btn-text text-left-force no-padding">
                                        <i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i>
                                        {{ $file["name"] }}
                                    </a>
                                </td>

                                <td>
                                    {{ $file["size"] }}
                                </td>

                                <td>
                                    {{ $file["modified_at"] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="panel-footer">
                    @if ($previous_dir !== $pwd)
                        <a href="{{ route("guides.view", [$previous_dir], false) }}" class="btn btn-link btn-text no-padding">
                            <i class="fa fa-fw fa-arrow-left" aria-hidden="true"></i>
                            &nbsp;
                            Previous Page
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
