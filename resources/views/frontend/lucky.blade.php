@extends('frontend.layout.main')
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="/lucky/js/hc-canvas-luckwheel.js"></script>
    <script>
        {{-- var prizes = []; --}}
        {{-- $(document).ready(function () { --}}



        {{-- }); --}}

        {{-- }); --}}
        var isPercentage = true;
        var prizes = <?php echo json_encode($data, JSON_HEX_TAG); ?>;
        // console.log(prizes);
        // var x = document.getElementById("myAudio");
        //
        // function playAudio() {
        //   x.play();
        // }
        //
        // function pauseAudio() {
        //   x.pause();
        // }
        document.addEventListener(
            "DOMContentLoaded",
            function() {
                // console.log('asdasds')
                hcLuckywheel.init({
                    id: "luckywheel",
                    config: function(callback) {
                        // console.log(callback);
                        callback &&
                            callback(prizes);
                    },
                    mode: "both",
                    getPrize: function(callback) {
                        // var rand = randomIndex(prizes);
                        // console.log(1111, rand);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('frontend.luckyProduct') }}",
                            type: "post",
                            dataType: "json",
                            data: {
                                prizes: prizes,
                            },
                            success: function(result) {
                                // console.log('result222', result)

                                $(".turn").html(result.luckyNumber)
                                if (result.message && result.message === 'err') {
                                    Swal.fire(
                                        'Bạn đã hết lượt quay'
                                    )
                                    return
                                }

                                var rand = result.result
                                var chances = rand;
                                callback && callback([rand, chances]);



                            }
                        });


                    },
                    gotBack: function(data) {
                        // console.log(222, new Date());

                        console.log('data', data)

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('frontend.getHistoryRolanty') }}",
                            type: "get",
                            dataType: "json",
                            success: function(result) {
                                console.log('result2', result)
                                let str = `<tr>
                                                <td>Tài khoản</td>
                                                <td>Phần thưởng</td>
                                                <td>Ngày quay</td>
                                            </tr>`;

                                result.showHistory.map((res) => {
                                    str += `<tr>
                                                <td>${res.user.userid}</td>
                                                <td>${res.product.prd_name}</td>
                                                <td>${res.created_at}</td>
                                            </tr>`
                                })

                                $(".historyLuckyContent").html(str)
                            }
                        });


                        if (data == null) {
                            Swal.fire(
                                'Chương trình kết thúc',
                                'Đã hết phần thưởng',
                                'error'
                            )
                        } else if (data == 'Chúc bạn may mắn lần sau') {
                            Swal.fire(
                                'Bạn không trúng thưởng',
                                data,
                                'error'
                            )
                        } else {
                            Swal.fire(
                                'Đã trúng giải',
                                data.text,
                                'success'
                            )
                        }
                    }
                });
            },
            false
        );

        function randomIndex(prizes) {
            if (isPercentage) {
                var counter = 1;
                for (let i = 0; i < prizes.length; i++) {
                    if (prizes[i].number == 0) {
                        counter++
                    }
                }
                if (counter == prizes.length) {
                    return null
                }
                return 1
            } else {
                console.log(1)

            }
        }
    </script>
@endsection
<link rel="stylesheet" href="/lucky/css/typo/typo.css" />
<link rel="stylesheet" href="/lucky/css/hc-canvas-luckwheel.css" />
<style type="text/css">
    .wrapper {
        background-color: #5f366e;
    }

    .hc-luckywheel {
        left: 50%;
        transform: translateX(-50%);
    }

    .boxTurn {
        background: rgba(240, 85, 48, .58039);
        font-size: 18px;
        color: #fff;
        width: 283px;
        padding: 15px;
        border-radius: 5px;
        margin: 18px auto 0;
        text-align: center;
    }

    .rulesTitle {
        /*background: rgba(240, 85, 48, .58039);*/
        padding: 5px 0;
        border-radius: 5px 5px 0 0;
        font-size: 44px;
        font-weight: 500;
        text-align: center;
    }

    .rulesContent {
        padding: 36px 25px;
        /*background: #f99c25;*/
        /*background: linear-gradient(*/
        /*        180deg, #f99c25 19%, #ffc908);*/
        border-radius: 0 0 5px 5px;
    }

    .rulesContent p {
        font-size: 22px;
        color: rgba(0, 0, 0, .7098);
        margin-bottom: 6px;
    }

    .infoAcc table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        background: #f99c25;
        background: linear-gradient(180deg, #f99c25 19%, #ffc908);
    }

    .infoAcc tr:nth-child(1) {
        background-color: rgba(240, 85, 48, .58039);
    }

    .infoAcc tr:nth-child(1) td {
        color: #fff;
    }

    .infoAcc td,
    th {
        border: 1px solid #000;
        text-align: left;
        padding: 8px;
    }

    .infoAcc td {
        color: #000;
        font-family: 'UVNThanhPho_R';
        margin-bottom: 26px;
        margin-top: 35px;
        font-size: 17px;
        text-align: center;
        padding-bottom: 12px;
    }

</style>
@section('content')
    <div class="content">
        <div class="bg">
            <div class="lucky-container position-relative" id="wrapper"
                style="overflow: hidden;    padding-top: 170px;padding-boittom: 170px;">
                <section id="luckywheel" class="hc-luckywheel">
                    <image src="/frontend/images/background/lucky-bg.png" style="
        width: 2254px;
        left: -858px;
        top: -208px;
        z-index: -1;z-index: -1;" class="bg-img position-absolute">
                        <div class="hc-luckywheel-container">
                            <canvas class="hc-luckywheel-canvas" width="500px" height="500px">Vòng Xoay May Mắn
                            </canvas>
                        </div>
                        <a class="hc-luckywheel-btn" href="javascript:;"></a>
                </section>
                <div class="boxTurn boxTurn2" style="width: 246px;margin-top:250px;">Lượt quay của bạn: <span
                        class="turn">{{ $luckyNumber }}</span></div>
                <!--      <audio id="myAudio">-->
                <!--        <source src="media/wheel_tick.mp3" type="audio/mpeg">-->
                <!--        Your browser does not support the audio element.-->
                <!--      </audio>-->

                <!--      <button onclick="playAudio()" type="button">Play Audio</button>-->
                <!--      <button onclick="pauseAudio()" type="button">Pause Audio</button>-->
            </div>
        </div>
    </div>
    <div class="container">
        <div class="rules">
            <div class="rulesTitle" style="color: #fff">Danh sách trúng thưởng</div>
            <div class="rulesContent">
                <div class="infoAcc" style="overflow: auto">
                    <table style="width:100%" class="historyLuckyContent">
                        <tr>
                            <td>Tài khoản</td>
                            <td>Phần thưởng</td>
                            <td>Ngày quay</td>

                        </tr>
                        @foreach ($showHistory as $value)
                            <tr>
                                <td>{{ $value['user']['userid'] }}</td>
                                <td>{{ $value['product']['prd_name'] }}</td>
                                <td>{{ $value['created_at'] }}</td>
                            </tr>
                        @endforeach

                    </table>
                    {{-- <span style="font-size: 20px">{{Auth::user()->userid}}</span> <br> --}}
                    {{-- <span style="font-size: 20px">Coin: {{Auth::user()->coin}}<b class="icon-price"></b></span> --}}
                </div>

            </div>
        </div>
    </div>
@endsection
