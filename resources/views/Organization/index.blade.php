@extends('app')

@section('title', trans('title.organisation').' - ' . $reportingOrg['reporting_organization_identifier'])

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="element-panel-heading">
                    <div><span class="pull-left">@lang('global.organisations')</span></div>
                </div>
                <div class="col-xs-12 element-content-wrapper full-width-wrapper">
                    <div class="element-description">
                        <p>Below you’ll find your organization (reporting organization) and all other organizations that you’ve listed as partner organization in your activities. Any organization
                            you’ll add here will be available as an option while associating an activity to a partner organization.</p>
                    </div>
                    <div class="panel-body panel-org-body panel__reporting-org">
                        <h3>@lang('global.reporting_organisation')</h3>
                        <table class="table table-striped">
                            <tbody>
                            <tr class="clickable-row" data-href="{{ route('organization.show', $reportingOrg->id) }}">
                                {{--<td>--}}
                                {{--<a href="{{ route('organization-data.edit', $reportingOrg->id)}}" class="edit-activity pull-right">@lang('global.edit')</a>--}}
                                {{--</td>--}}
                                <td class="organisation-name" width="70%">
                                    <a href="{{route('organization.show', $reportingOrg->id)}}">
                                        {{ $reportingOrg->reporting_org }}
                                    </a>
                                    <span class="identifier">{{ $reportingOrg->identifier }}</span>
                                </td>
                                <td class="sector" width="15%">
                                    {{ $reportingOrg->type ? $getCode->getCodeNameOnly('OrganizationType', $reportingOrg->type, -4, 'Organization') : '' }}
                                </td>
                                <td width="10%">
                                    <div class="activity__status activity-status-{{ $reportingOrg->getStatus() }}">
                                        <span>{{ $reportingOrg->getStatus() }}</span>
                                    </div>
                                </td>
                                <td width="50px">
                                    <div class="view-more">
                                        <a href="#">⋯</a>
                                        <div class="view-more-actions">
                                            <ul>
                                                {{--<li><a href="#" class="merge-with">Merge with ...</a></li>--}}
                                                <li>
                                                    <a href="{{ route('organization.show', $reportingOrg->id)}}">@lang('global.edit_this_organisation')</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body panel-org-body panel__partner-org">
                        @if (count($participatingOrg))<h3>@lang('global.partner_organisations')</h3>@endif
                        <a class="pull-right add-new-organisation" href="{{ route('organization.create', session('org_id')) }}">@lang('global.add_a_new_organisation')</a>
                        <table class="table table-striped">
                            <tbody>
                            @foreach($participatingOrg as $orgData)
                                <tr class="clickable-row" data-href="{{route('organization.show', $orgData->id)}}">
                                    <td class="organisation-name" width="55%">
                                        <a href="{{route('organization.show', $orgData->id)}}">
                                            {{ $orgData->name[0]['narrative'] ? $orgData->name[0]['narrative'] : trans('global.name_not_given')}}
                                        </a>
                                        <span class="identifier">
                                            {{ $orgData->identifier }}
                                        </span>
                                        @if($orgData->is_publisher)
                                            <span class="{{ $orgData->is_publisher ? 'is-publisher' : '' }}">publisher</span>
                                        @endif
                                    </td>
                                    <td class="activities" width="15%">
                                        {!! $orgData->includedActivities() !!}
                                    </td>
                                    <td class="sector" width="15%">
                                        {{ $getCode->getCodeNameOnly('OrganizationType', $orgData->type, -4, 'Organization') }}
                                    </td>
                                    <td width="10%">
                                        <div class="activity__status activity-status-{{ $orgData->getStatus() }}">
                                            <span>{{ $orgData->getStatus() }}</span>
                                        </div>
                                    </td>
                                    <td width="50px">
                                        <div class="view-more">
                                            <a href="javascript:void(0)" class="{{ (count($participatingOrg) > 1) ?: 'hidden'}}">⋯</a>
                                            <div class="view-more-actions {{ (count($participatingOrg) > 1) ?: 'hidden'}}">
                                                <ul>
                                                    @if (count($participatingOrg) > 1)
                                                        <li>
                                                            <a href="javascript:void(0)" data-org-id="{{ $orgData->id }}" data-org-name="{{ $orgData->name[0]['narrative'] }}"
                                                               class="merge-with mergeWithTrigger">@lang('organisation-data.merge_with_label')
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (count($orgData->used_by) == 0)
                                                        {{--<li>--}}
                                                        {{--<a href="{{ route('organization-data.edit', $orgData->id)}}">@lang('global.edit_this_organisation')</a>--}}
                                                        {{--</li>--}}
                                                        <li>
                                                            <form data-route="{{ route('organization-data.delete', $orgData->id)}}" method="POST" class="delete-org-data-form">
                                                                {{ csrf_field() }}
                                                                <input type="submit" value="{{ trans('global.delete_this_organisation') }}" class="delete-org-data-button">
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade org-modal" tabindex="-1" role="dialog" id="mergeWithModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('organisation-data.merge_with_an_organisation')</h4>
                </div>
                <form method="POST" id="organization-merger">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div id="partner-org-body"></div>
                        {{--<div class="merge-with__wrap">--}}
                        {{----}}
                        {{--</div>--}}
                        <div id="organisation-merger-info-container"></div>
                        <div id="activity-list-container" class="scroll-list"></div>
                    </div>
                    <div class="modal-footer text-center">
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                        <input type="submit" class="btn btn-primary btn-merge" value="Merge these organisations"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
      $(document).ready(function () {
        var partnerOrganisations = {!! json_encode($participatingOrg) !!};
        var partnerOrganisationContainer = $('#partner-org-body');
        var infoContainer = $('#organisation-merger-info-container');
        var activityListContainer = $('#activity-list-container');
        var mergerRoute = "{!! route('organization.merge-organization-data', ['organization-from', 'organization-to']) !!}";
        var form = $('#organization-merger');

        $('.delete-org-data-button').on('click', function (event) {
          event.preventDefault();
          var deleteOrgDataForm = $(this).closest('.delete-org-data-form');

          var route = deleteOrgDataForm.attr('data-route');
          console.log(route);
          $('body').append('' +
            '<div class="modal" id="delDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h4 class="modal-title" id="myModalLabel"></h4>' +
            '</div>' +
            '<div class="modal-body"></div>' +
            '<div class="modal-footer"></div>' +
            '</div>' +
            '</div>' +
            '</div>');

          var delDialog = $('#delDialog');

          var buttons = '' +
            '<button class="btn btn_del delete-org-data-confirm" type="button">' + localisedData['yes'] + '</button>' +
            '<button class="btn btn-default" type="button"  data-dismiss="modal">' + localisedData['no'] + '</button>';

          $('.modal-header .modal-title', delDialog).html(localisedData['delete_confirmation']);
          $('.modal-body', delDialog).html(localisedData['delete_sure']);
          $('.modal-footer', delDialog).html(buttons);

          delDialog.modal('show');
          $('.delete-org-data-confirm').on('click', function (event) {
            deleteOrgDataForm.attr('action', route);
            deleteOrgDataForm.trigger('submit');
          });
        });

        $('.mergeWithTrigger').on('click', function (event) {
          if ($(".merge-with__wrap").length > 0) {
            $(".merge-with__wrap").remove();
          }

          if (activityListContainer.is(':empty')) {
            activityListContainer.hide();
          } else {
            activityListContainer.show();
          }
          partnerOrganisationContainer.empty();
          infoContainer.empty();
          activityListContainer.empty();

          var self = $(this);
          var organisationName = self.attr('data-org-name');
          var organisationId = self.attr('data-org-id');
          var route = '/get-activity-titles/' + organisationId;

          var requiredOrganisations = $.map(partnerOrganisations, function (org, index) {
            if (org.id != organisationId) {
              return org;
            }
          });

          var actionInfo = "Please choose an organisation from below you want to merge org_name with:";
          var info = "Please remember merging organisation_name_placeholder with other organization will remove organisation_name_placeholder from your list and transfer all (see below) activities from organisation_name_placeholder to the organization you choose.";

          partnerOrganisationContainer.html('<p class="text-center">' + actionInfo.replace(/org_name/, '<strong>' + organisationName + '</strong>') + '</p>');

          $.map(requiredOrganisations, function (org, index) {
            $('<label>\n' +
              '<input required="true" type="radio" class="organization-to-be-merged-with" name="merge_target" value="' + org.id + '">' + org.name[0].narrative +
              '</label>').appendTo(partnerOrganisationContainer)
          });

          var rules = {
            merge_target: {
              required: true
            }
          };

          var messages = {
            merge_target: {
              required: 'Please select an Organisation.'
            }
          };


          $.ajax(route).success(function (response) {
            var mainContainer = $('<div/>', {
              class: 'merge-with__wrap'
            });

            if (response.length) {
              infoContainer.html('<div>' + info.replace(/organisation_name_placeholder/g, '<strong>' + organisationName + '</strong>') + '</div>').appendTo(mainContainer);
              $.map(response, function (value, index) {
                $('<li>' + value + '</li>').appendTo(activityListContainer);
              });

              activityListContainer.show();
              activityListContainer.appendTo(mainContainer);
              mainContainer.appendTo($('.modal-body'));
            }

            form.validate({
              rules: rules,
              messages: messages,
              errorPlacement: function (error, element) {
                error.insertBefore(element);
              }
            });
          });

          $('.organization-to-be-merged-with').click(function () {
            var that = $(this);
            form.find('input[type="submit"]').attr('disabled', false);
            var formMergerRoute = mergerRoute.replace(/organization-from/g, organisationId);
            formMergerRoute = formMergerRoute.replace(/organization-to/g, that.val());
            form.attr('action', formMergerRoute);
          });

          form.find('input[type="submit"]').attr('disabled', false);

          $('#mergeWithModal').modal('show');
        });
      });
    </script>
@endsection
