<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.staticfile.org/jquery/3.4.1/jquery.min.js"></script>
    <script>
        /**
         * 与GatewayWorker建立websocket连接，域名和端口改为你实际的域名端口，
         * 其中端口为Gateway端口，即start_gateway.php指定的端口。
         * start_gateway.php 中需要指定websocket协议，像这样
         * $gateway = new Gateway(websocket://0.0.0.0:7272);
         */
        let ws = new WebSocket("ws://workerman.tech:23460");
        let wsHeart;
        ws.onopen = function(e){
            console.info("与服务端连接成功。");
            console.info("设置向服务端发送心跳包字符串 setInterval(heart,55000)");
            wsHeart = setInterval(function(){
                ws.send('{"type":"ping"}');
            },55000);
        }

        //心跳处理
        //获取会员id
        ws.onclose = function(e){
            if (typeof wsHeart != "undefined") {
                clearInterval(wsHeart);
                console.log('关闭心跳检测');
            }
        }

        // 服务端主动推送消息时会触发这里的onmessage
        ws.onmessage = function(e){
            // json数据转换成js对象
            let data = eval("("+e.data+")");
            let type = data.type || '';
            let name = 'Stock' + type.substr(0,1).toUpperCase() + type.substr(1);
            if (typeof window[name] === "function") {
                new window[name](data);
            }else {
                console.log(data);
            }
        };
    </script>
    <script>
        function StockConnect (data){
            // 可以放到服务器的onConnect事件中操作，避免client_id泄露
            $.post(data.url, {client_id: data.client_id}, function(msg){
                console.log(msg);
            }, 'json');
        }
    </script>
    <script>
        function StockMessage (data){
            this._init_(data);
        }

        StockMessage.prototype = {
            constructor : StockMessage,
            _init_ : function(data) {
                console.log(data);
            }
        }
    </script>
</html>
