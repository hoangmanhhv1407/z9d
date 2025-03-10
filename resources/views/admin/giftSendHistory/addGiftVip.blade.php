@extends('admin.layout.main')
@section('content')
    @include('admin/layout/header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('admin/layout/sidebar')
        <div class="page-content-wrapper">
            <div class="page-content">
                @include('admin/layout/message')
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="index.html">Gui Qua Cho Vip</a>
                        </li>
                    </ul>
                </div>
                <div class="inbox">
                    <div class="box">
                        <div class="box-body ">
                            <!-- Horizontal Form -->
                            <div class="box box-primary clearfix">
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="box-body clearfix">
                                        <div class="col-sm-2">
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                                                <div class="col-sm-10">
                                                    <p style="color: red">* Lưu ý: chỉ được chọn cấp Vip hoặc tài khoản
                                                        để thêm lượt quay</p>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="product" class="col-sm-2 control-label">Quà</label>
                                                <div class="col-sm-10">
                                                    <select name="gift" id="product" class="form-control"
                                                        style="margin-right: 5px">
                                                        <option value="">-- Chọn vật phẩm--</option>
                                                        @foreach ($productList as $key => $item)
                                                            <option value="{{ $item->id }}">{{ $item->prd_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-2 control-label">Cấp Vip</label>
                                                <div class="col-sm-10">

                                                    <select class="js-example-responsive select-vip" name="vip"
                                                            style="width: 100%">
                                                        <option value="">-- Chọn --</option>
                                                        <option value="0-499">0 - 499 (VIP 0)</option>
                                                        <option value="500-999">500 - 1000 (VIP 1)</option>
                                                        <option value="1000-1999">1000 - 1999 (VIP 2)</option>
                                                        <option value="2000-2999">2000 - 2999 (VIP 3)</option>
                                                        <option value="3000-3999">3000 - 3999 (VIP 4)</option>
                                                        <option value="4000-4999">4000 - 4999 (VIP 5)</option>
                                                        <option value="5000-9999">5000 - 9999 (VIP 6)</option>
                                                        <option value="10000-10000000000">trên 10000 (VIP 7)</option>
                                                    </select>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                    <!-- /.box-body -->
                                    <div class="box-footer text-center">
                                        <a href="{{ route('admin.giftSendHistory.index') }}" class="btn btn-danger">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-info">Gửi quà</button>
                                    </div>
                                    <!-- /.box-footer -->
                                </form>
                                <hr>
                                <div>
                                    <table class="table no-margin">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên tài khoản</th>
                                            <th>Tổng coin</th>
                                            <th>Lượt quay</th>

                                        </tr>
                                        </thead>
                                        <tbody class="box-content">


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner">
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
	
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $(".js-example-responsive").select2({
                width: 'resolve' // need to override the changed default
            });
            $('.select-vip').on('change', function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let b = $('.select-vip').val()
                let url = `{{route('admin.giftSendHistory.getUserVip2')}}`
                $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    data: {
                        gift: b,
                    },
                    success: function (result) {
                        console.log('result', result)
                        let str = '';
                        result.data.map((res, i) => {
                            str += ` <tr>
                                            <td>${i + 1}</td>
                                            <td>${res.userid}</td>
                                            <td>${res.total}</td>
                                            <td>${res.luckyNumber}</td>

                                        </tr>`
                        })

                        $('.box-content').html(str)
                    }
                });
            });
        });
    </script>
@endsection