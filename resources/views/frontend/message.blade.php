@if (Session::has('message') && ! is_array(Session::get('message')))
    <script type="text/javascript">
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.{{ Session::get('alert') }}("{{ Session::get('message') }}");

    </script>
@endif
@if(Session::has('notification'))

    <div class="modal fade" id="checkStatus" role="dialog">
        <div class="modal-dialog " style="margin-top: 100px">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="padding-right: 30px;padding-left: 30px;text-align: center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thông báo</h4>
                </div>
                <div class="modal-body" style="padding-right: 50px;padding-left: 50px;text-align: center">
                    <p class="text-center" style="line-height: 23px;">{!! Session::get('notification') !!}</p>
                </div>
                <div class="modal-footer">
                    {{--<button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>--}}
                </div>
            </div>

        </div>
    </div>
@endif