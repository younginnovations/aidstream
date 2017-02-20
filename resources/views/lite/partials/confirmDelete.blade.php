<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="delete-modal">
    <div class="modal-dialog modal-lg" role="document">     
        <div class="modal-content">         
            <div class="modal-header">         
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left" id="myModalLabel">@lang('lite/global.confirmation')</h4>
            </div>
            <form action="" method="POST" id="delete-form">
                {{ csrf_field() }}
                <input id="index" type="hidden" value="" name="index">
                <div class="modal-body">
                    <p id="modal-message"></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-delete-transaction" class="btn btn-primary">@lang('lite/settings.yes')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('lite/settings.no')</button>
                </div>
            </form>
        </div>
    </div>
</div>
