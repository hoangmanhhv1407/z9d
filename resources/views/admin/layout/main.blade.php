<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog-Oricoin</title>
    <base href="{{ asset('') }}">


    <!-- Facebook Pixel Code -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::asset('admin/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/jquery.fancybox.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/blueimp-gallery.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ URL::asset('admin/css/jquery.fileupload.css') }}" rel="stylesheet" type="text/css"/> --}}
    {{-- <link href="{{ URL::asset('admin/css/jquery.fileupload-ui.css') }}" rel="stylesheet" type="text/css"/> --}}
    <link href="{{ URL::asset('admin/css/inbox.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/components.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/layout.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/darkblue.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('favicon.ico') }}" rel="shortcut icon" />
    {{-- <link href="{{ URL::asset('admin/vendor/summernote/summernote.css') }}" rel="shortcut icon"/> --}}
    <link href="/frontend/vendor/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!--End of Tawk.to Script-->
    <!--Theme style-->
    {{-- <link href="{{ URL::asset('index/css/main.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ URL::asset('index/css/responsive.css') }}" rel="stylesheet"> --}}
    <style>
        .flash-message .alert {
            width: 250px;
            top: 10px;
            right: 20px;
            position: absolute;
            z-index: 9999;
        }

    </style>
</head>

<body>

    @yield('content')


    <script src="{{ URL::asset('admin/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery-migrate.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    {{-- <script src="{{ URL::asset('admin/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script> --}}
    <script src="{{ URL::asset('admin/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.cokie.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.fancybox.pack.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-wysihtml5.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.ui.widget.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/tmpl.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/load-image.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/canvas-to-blob.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.blueimp-gallery.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/jquery.iframe-transport.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload-process.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload-image.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload-audio.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload-video.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload-validate.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ URL::asset('admin/js/jquery.fileupload-ui.js') }}" type="text/javascript"></script> --}}
    <script src="{{ URL::asset('admin/js/metronic.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/layout.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/quick-sidebar.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/demo.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/js/inbox.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ URL::asset('admin/vendor/ckeditor/ckeditor.js') }}" type="text/javascript"></script> --}}
    <script src="{{ URL::asset('admin/vendor/main.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ URL::asset('admin/vendor/summernote/summernote.js') }}" type="text/javascript"></script> --}}
    @yield('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="/frontend/vendor/toastr/toastr.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    {{-- <script> --}}
    {{-- CKEDITOR.replace( 'my-editor' ); --}}
    {{-- </script> --}}
    <script>
        jQuery(document).ready(function() {
            // initiate layout and plugins
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init(); // init quick sidebar
            Demo.init(); // init demo features
            Inbox.init();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Nội dung',
                tabsize: 2,
                height: 160,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['picture', ['picture']],
                ],
                fontSizes: ['12', '16', '18', '24'],
            });
            $('.summernote').summernote({
                placeholder: 'Nội dung',
                tabsize: 2,
                height: 160,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['picture', ['picture']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                fontSizes: ['12', '16', '18', '24'],
            });
            $.get('/admins/relog-momo/check')
                .done(function(data) {
                    console.log(data);
                    if (data === 'success') {
                        toastr.info('Momo vẫn đang được kết nối', 'Đã kết nối');
                    }
                    else{
                        toastr.error('Liên kết momo thất bại, vui lòng đăng nhập lại!', 'Không đăng nhập được Momo');
                    }
                });
        });
        /*Ajax upload image*/
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imgInp").change(function() {
            readURL(this);
        });

        /*Alert sideup*/
        $(function() {
            $('.flash-message .alert').delay(3000).slideUp();
        })
    </script>
    @include('frontend.message')

</body>

</html>
