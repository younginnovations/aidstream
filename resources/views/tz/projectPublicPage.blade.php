@extends('tz.base.app')

@section('content')
    <section class="main-container">
        <div class="container">
            <div class="title-section">
                <h1>{{$orgDetails->name}}</h1>
                <div class="location"><span>{{$orgDetails->address}}</span></div>
                <div class="social-logo">
                    @if($user->email)
                        <span><a href="mailto:{{$user->email}}" class="mail">{{$user->email}}</a></span>
                    @endif
                    @if($orgDetails->organization_url)
                        <span><a href="{{$orgDetails->organization_url}}" class="web">{{$orgDetails->organization_url}}</a></span>
                    @endif
                    @if($orgDetails->twitter)
                        <span><a href="http://www.twitter.com/{{$orgDetails->twitter}}" target="_blank"
                                 class="twitter">{{$orgDetails->twitter}}</a></span>
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="header-name-value name-value-section clearfix">
                        <dl class="col-md-3">
                            <dt> Total Disbursements</dt>
                            <dd class="amount">${{ $transactionCount['disbursement'] }}</dd>
                        </dl>
                        <dl class="col-md-3">
                            <dt>Total Expenditures</dt>
                            <dd class="amount">${{ $transactionCount['expenditure'] }}</dd>
                        </dl>
                        <dl class="col-md-3">
                            <dt> Total Incoming Funds</dt>
                            <dd class="amount">${{ $transactionCount['incoming_fund'] }}</dd>
                        </dl>

                    </div>

                </div> {{--  row close--}}
            </div>
        </div> {{--  container close--}}


        <div class="container container--shadow">
            <div class="col-md-12">
                <table class="table table-striped custom-table" id="data-table">
                    <thead>
                    <tr>
                        <th width="40%">Project Title</th>
                        <th class="">Project Identifier</th>
                        <th class="">Last Updated</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <td class="bold-col">{{$project->title[0]['narrative']}}</td>
                            <td>{{$project->identifier['activity_identifier']}}</td>
                            <td class="light-col"> {{ formatDate($project->updated_at) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
