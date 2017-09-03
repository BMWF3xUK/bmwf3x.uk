@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include("components.sidebar")
        </div>

        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Guides and Other Documents</div>

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
                            <tr onclick="window.location = '{{ route("guides.view", [trim("{$pwd}/{$dir}", "/")], false) }}'" style="cursor: pointer;">
                                <td>
                                    <i class="fa fa-fw fa-folder" aria-hidden="true"></i>
                                    {{ $dir }}
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
                            <tr onclick="window.location = '{{ route("guides.download", [trim("{$pwd}/{$file["name"]}", "/")], false) }}'" style="cursor: pointer;">
                                <td>
                                    <i class="fa fa-fw fa-file-text-o" aria-hidden="true"></i>
                                    {{ $file["name"] }}
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
            </div>
        </div>
    </div>
</div>
@endsection
