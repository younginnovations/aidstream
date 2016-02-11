@extends('app')

@section('title', 'Organization - ' . $reporting_org['reporting_organization_identifier'])

@section('content')
	@inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
        	@include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.breadcrumb')
            	<?php
					$status_label = ['draft', 'completed', 'verified', 'published'];
					$btn_status_label = ['Completed', 'Verified', 'Published'];
					$btn_text = $status > 2 ? "" : $btn_status_label[$status];
				?>
                <div class="element-panel-heading">
                	<span class="pull-left">Organization</span>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        	        <div class="activity-status activity-status-{{ $status_label[$status] }}">
						<ol>
							@foreach($status_label as $key => $val)
								@if($key == $status)
									<li class="active"><span>{{ $val }}</span></li>
								@else
									<li><span>{{ $val }}</span></li>
								@endif
							@endforeach
						</ol>
						@if($btn_text != "")
							<form method="POST" id="change_status" class="pull-right" action="{{ url('/organization/' . Auth::user()->org_id . '/update-status') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
								<input type="hidden" name="status" value="{{ $status + 1 }}">
								@if($status == 2)
									<input type="button" value="Mark as {{ $btn_text }}" class="btn_confirm"
										   data-title="Confirmation" data-message="Are you sure you want to Publish?">
								@else
									<input type="submit" value="Mark as {{ $btn_text }}">
								@endif
							</form>
						@endif
					</div>
	                <div class="panel panel-default panel-element-detail">
						<div class="panel-body">
							@if(!empty($reporting_org))
							<div class="panel panel-default">
								<div class="panel-heading">Reporting Organization
								<a href="#" class="edit-element">edit</a>
								</div>
								<div class="panel-body panel-element-body row">
									<div class="col-xs-12 col-md-12">
										<div class="col-xs-12 col-xs-4">Ref:</div>
										<div class="col-xs-12 col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
									</div>
									<div class="col-xs-12 col-md-12">
										<div class="col-xs-12 col-xs-4">Type:</div>
										<div class="col-xs-12 col-xs-8">{{ $reporting_org['reporting_organization_type'] }}</div>
									</div>
									@foreach($reporting_org['narrative'] as $narrative)
									<div class="col-xs-12 col-md-12">
										<div class="col-xs-12 col-xs-4">Narrative Text:</div>
										<div class="col-xs-12 col-xs-8">{{ $narrative['narrative'] . ' [' . $narrative['language'] . ']' }}</div>
									</div>
									@endforeach
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">Organization Identifier
								<a href="#" class="edit-element">edit</a>
								</div>
								<div class="panel-body panel-element-body row">
									<div class="col-xs-12 col-md-12">
										<div class="col-xs-12 col-xs-4">Text:</div>
										<div class="col-xs-12 col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
									</div>
								</div>
							</div>
							@endif

							@if(!empty($org_name))
							<div class="panel panel-default">
								<div class="panel-heading">Name
								<a href="#" class="edit-element">edit</a>
								</div>
								<div class="panel-body panel-element-body row">
									@foreach($org_name as $name)
									<div class="col-xs-12 col-md-12">
										<div class="col-xs-12 col-xs-4">Text:</div>
										<div class="col-xs-12 col-xs-8">{{ $name['narrative'] . ' [' . $name['language'] . ']' }}</div>
										@if(isset($name['new_field']))
										<div class="col-xs-12 col-xs-4">New Field Value:</div>
										<div class="col-xs-12 col-xs-8">{{ $name['new_field'] }}</div>
										@endif
									</div>
									@endforeach
								</div>
							</div>
							@endif

							@if(!empty($total_budget))
								<div class="panel panel-default">
									<div class="panel-heading">Total Budget
										<a href="#" class="edit-element">edit</a>
									</div>
									<div class="panel-body row panel-level-1">
										@foreach($total_budget as $totalBudget)
											<div class="panel panel-default">
												<div class="panel-heading">Value</div>
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Text:</div>
														<div class="col-xs-12 col-xs-8">{{ $totalBudget['value'][0]['amount']}}</div>
													</div>
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Value Date:</div>
														<div class="col-xs-12 col-xs-8">{{ $totalBudget['value'][0]['value_date'] }}</div>
													</div>
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Currency:</div>
														<div class="col-xs-12 col-xs-8">{{ $totalBudget['value'][0]['currency']}}</div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">Period Start</div>
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Iso Date:</div>
														<div class="col-xs-12 col-xs-8">{{ $totalBudget['period_start'][0]['date'] }}</div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">Period End</div>
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Iso Date:</div>
														<div class="col-xs-12 col-xs-8">{{ $totalBudget['period_end'][0]['date'] }}</div>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
	                                            <div class="panel panel-default">
													<div class="panel-heading">Budget Line</div>
													<div class="panel-body row">
														@foreach($totalBudget['budget_line'] as $budgetLine)
															<div class="panel panel-default">
																<div class="panel-body panel-element-body row">
																	<div class="col-xs-12 col-md-12">
																		<div class="col-xs-12 col-xs-4">Reference:</div>
																		<div class="col-xs-12 col-xs-8">{{ $budgetLine['reference']}}</div>
																	</div>
																</div>
															</div>
															<div class="panel panel-default">
																<div class="panel-heading">Value</div>
																<div class="panel-body panel-element-body row">
																	<div class="col-xs-12 col-md-12">
																		<div class="col-xs-12 col-xs-4">Text:</div>
																		<div class="col-xs-12 col-xs-8">{{ $budgetLine['value'][0]['amount']}}</div>
																	</div>
																	<div class="col-xs-12 col-md-12">
																		<div class="col-xs-12 col-xs-4">Value Date:</div>
																		<div class="col-xs-12 col-xs-8">{{ $budgetLine['value'][0]['value_date'] }}</div>
																	</div>
																	<div class="col-xs-12 col-md-12">
																		<div class="col-xs-12 col-xs-4">Currency:</div>
																		<div class="col-xs-12 col-xs-8">{{ $budgetLine['value'][0]['currency']}}</div>
																	</div>
																</div>
															</div>
															<div class="panel panel-default">
																@foreach($budgetLine['narrative'] as $budgetLineNarrative)
																	<div class="panel-heading">Narrative</div>
																	<div class="panel-body panel-element-body row">
																		<div class="col-xs-12 col-md-12">
																			<div class="col-xs-12 col-xs-4">Text:</div>
																			<div class="col-xs-12 col-xs-8">{{ $budgetLineNarrative['narrative'] . ' [' . $budgetLineNarrative['language'] . ']' }}</div>
																		</div>
																	</div>
																@endforeach
															</div>
														@endforeach
													</div>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							@endif

						@if(!empty($recipient_organization_budget))
							<div class="panel panel-default">
								<div class="panel-heading">Recipient Organization Budget
									<a href="#" class="edit-element">edit</a>
								</div>
								<div class="panel-body panel-level-1 row">
									@foreach($recipient_organization_budget as $recipientOrgBudget)
										<div class="panel panel-default">
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">ref:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['recipient_organization'][0]['ref'] }}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											@foreach($recipientOrgBudget['narrative'] as $recipientOrgBudgetNarrative)
												<div class="panel-heading">Narrative</div>
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Text:</div>
														<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetNarrative['narrative'] . ' [' . $recipientOrgBudgetNarrative['language'] . ']' }}</div>
													</div>
												</div>
											@endforeach
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Value</div>
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Text:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['amount']}}</div>
												</div>
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Value Date:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['value_date'] }}</div>
												</div>
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Currency:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['value'][0]['currency']}}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Period Start</div>
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Iso Date:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['period_start'][0]['date'] }}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Period End</div>
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Iso Date:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudget['period_end'][0]['date'] }}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="col-xs-12 col-md-12 col-lg-12 panel-level-2">
											<div class="panel-heading">Budget Line</div>
											<div class="panel-body row">
												@foreach($recipientOrgBudget['budget_line'] as $recipientOrgBudgetLine)
													<div class="panel panel-default">
														<div class="panel-body panel-element-body row">
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Reference:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['reference']}}</div>
															</div>
														</div>
													</div>
													<div class="panel panel-default">
														<div class="panel-heading">Value</div>
														<div class="panel-body panel-element-body row">
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Text:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['amount']}}</div>
															</div>
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Value Date:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['value_date'] }}</div>
															</div>
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Currency:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLine['value'][0]['currency']}}</div>
															</div>
														</div>
													</div>
													<div class="panel panel-default">
														@foreach($recipientOrgBudgetLine['narrative'] as $recipientOrgBudgetLineNarrative)
															<div class="panel-heading">Narrative</div>
															<div class="panel-body panel-element-body row">
																<div class="col-xs-12 col-md-12">
																	<div class="col-xs-12 col-xs-4">Text:</div>
																	<div class="col-xs-12 col-xs-8">{{ $recipientOrgBudgetLineNarrative['narrative'] . ' [' . $recipientOrgBudgetLineNarrative['language'] . ']' }}</div>
																</div>
															</div>
														@endforeach
													</div>
												@endforeach
											</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						@endif

						@if(!empty($recipient_country_budget))
							<div class="panel panel-default">
								<div class="panel-heading">Recipient Country
									<a href="#" class="edit-element">edit</a>
								</div>
								<div class="panel-body panel-element-body row">
									@foreach($recipient_country_budget as $recipientCountryBudget)
										<div class="panel panel-default">
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Code:</div>
													<div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Country', $recipientCountryBudget['recipient_country'][0]['code'])}}</div>
												</div>
												@foreach($recipientCountryBudget['recipient_country'][0]['narrative'] as $recipientCountryNarrative)
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Text:</div>
														<div class="col-xs-12 col-xs-8">{{ $recipientCountryNarrative['narrative'] . ' [' . $recipientCountryNarrative['language'] . ']' }}</div>
													</div>
												@endforeach
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Value</div>
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Text:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['amount']}}</div>
												</div>
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Value Date:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['value_date'] }}</div>
												</div>
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Currency:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['value'][0]['currency']}}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Period Start</div>
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Iso Date:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['period_start'][0]['date'] }}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Period End</div>
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Iso Date:</div>
													<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudget['period_end'][0]['date'] }}</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Budget Line</div>
											<div class="panel-body panel-element-body row">
												@foreach($recipientCountryBudget['budget_line'] as $recipientCountryBudgetLine)
													<div class="panel panel-default">
														<div class="panel-body panel-element-body row">
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Reference:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['reference']}}</div>
															</div>
														</div>
													</div>
													<div class="panel panel-default">
														<div class="panel-heading">Value</div>
														<div class="panel-body panel-element-body row">
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Text:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['amount']}}</div>
															</div>
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Value Date:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['value_date']}}</div>
															</div>
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Currency:</div>
																<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLine['value'][0]['currency']}}</div>
															</div>
														</div>
													</div>
													<div class="panel panel-default">
														@foreach($recipientCountryBudgetLine['narrative'] as $recipientCountryBudgetLineNarrative)
															<div class="panel-heading">Narrative</div>
															<div class="panel-body panel-element-body row">
																<div class="col-xs-12 col-md-12">
																	<div class="col-xs-12 col-xs-4">Text:</div>
																	<div class="col-xs-12 col-xs-8">{{ $recipientCountryBudgetLineNarrative['narrative'] . ' [' . $recipientCountryBudgetLineNarrative['language'] . ']' }}</div>
																</div>
															</div>
														@endforeach
													</div>
												@endforeach
											</div>
										</div>
									@endforeach
								</div>
							</div>
						@endif

						@if(!empty($document_link))
							<div class="panel panel-default">
								<div class="panel-heading">Document Link
									<a href="#" class="edit-element">edit</a>
								</div>
								<div class="panel-body panel-element-body row">
									@foreach($document_link as $documentLink)
										<div class="panel panel-default">
											<div class="panel-body panel-element-body row">
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Url:</div>
													<div class="col-xs-12 col-xs-8">{{ $documentLink['url']}}</div>
												</div>
												<div class="col-xs-12 col-md-12">
													<div class="col-xs-12 col-xs-4">Format:</div>
													<div class="col-xs-12 col-xs-8">{{ $documentLink['format']}}</div>
												</div>
												@foreach($documentLink['narrative'] as $documentLinkNarrative)
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Narrative Text:</div>
														<div class="col-xs-12 col-xs-8">{{ $documentLinkNarrative['narrative'] . ' [' . $documentLinkNarrative['language'] . ']' }}</div>
													</div>
												@endforeach
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Category</div>
											@foreach($documentLink['category'] as $documentLinkCategory)
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Code:</div>
														<div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('DocumentCategory', $documentLinkCategory['code'])}}</div>
													</div>
												</div>
											@endforeach
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Language</div>
											@foreach($documentLink['language'] as $documentLinkLanguage)
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Code:</div>
														<div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Language', $documentLinkLanguage['language'])}}</div>
													</div>
												</div>
											@endforeach
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">Recipient Country</div>
											@foreach($documentLink['recipient_country'] as $documentLinkRecipientCountry)
												<div class="panel-body panel-element-body row">
													<div class="col-xs-12 col-md-12">
														<div class="col-xs-12 col-xs-4">Code:</div>
														<div class="col-xs-12 col-xs-8">{{ $code->getOrganizationCodeName('Country', $documentLinkRecipientCountry['code'])}}</div>
													</div>
												</div>
												<div class="panel panel-default">
													@foreach($documentLinkRecipientCountry['narrative'] as $documentLinkNarrative)
														<div class="panel-heading">Narrative</div>
														<div class="panel-body panel-element-body row">
															<div class="col-xs-12 col-md-12">
																<div class="col-xs-12 col-xs-4">Text:</div>
																<div class="col-xs-12 col-xs-8">{{ $documentLinkNarrative['narrative'] . ' [' . $documentLinkNarrative['language'] . ']' }}</div>
															</div>
														</div>
													@endforeach
												</div>
											@endforeach
										</div>
									@endforeach
								</div>
							</div>
						@endif
						</div>
					</div>
				</div>
            	@include('includes.menu_org')
        	</div>
    	</div>
   </div>
@endsection
