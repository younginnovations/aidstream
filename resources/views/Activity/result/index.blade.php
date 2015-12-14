@extends('app')

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="view_result" tabindex="-1" role="dialog" aria-labelledby="view_result_label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Result Detail</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-panel-template hidden">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
                        </div>
                    </div>
                </div>
                <div class="modal-row-template hidden">
                    <div class="clearfix">
                        <div class="col-sm-4 view_label"></div>
                        <div class="col-sm-8 view_value"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container main-container">
        <div class="row">
        @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                <div class="panel-content-heading">Activity Results</div>
                <div class="col-xs-8 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        @if(count($results) > 0)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $resultIndex=>$result)
                                <tr>
                                    <td>{{ $resultIndex + 1 }}</td>
                                    <td class="activity_title">
                                        {{ $result->title }}
                                    </td>
                                    <td>
                                        {{ $result->type }}
                                    </td>
                                    <td>
                                        <div class="activity_actions">
                                            <a href="{{ route('activity.result.show', [$id, $result->id]) }}" data-result="{{ json_encode($result->result) }}" data-toggle="modal" data-target="#view_result" class="view">View</a>
                                            <a href="{{ route('activity.result.edit', [$id, $result->id]) }}" class="edit">Edit</a>
                                            <a href="{{ url(sprintf('activity/%s/result/%s/delete', $id, $result->id)) }}" class="delete">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                            <div class="text-center no-data">No results Created Yet.</div>
                        @endif
                        <a href="{{ route('activity.result.create', $id) }}" class="add">Add Another Result</a>

                    </div>
                </div>
            </div>
            @include('includes.activity.element_menu')
            </div>
        </div>
    </div>
@endsection

