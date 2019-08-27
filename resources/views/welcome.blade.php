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
        function createWebSocket(url) {
            let _this = this
            this.ws = null
            this.connectTimes = 0
            this.connect = function () {
                _this.ws = new WebSocket(url);
                _this.ws.onopen = function(e){
                    _this.heartCheck.start()
                    _this.reConnect.reset()
                }

                //心跳处理
                _this.ws.onclose = function(e){
                    _this.heartCheck.reset()
                    _this.reConnect.start()
                }

                // 服务端主动推送消息时会触发这里的onmessage
                _this.ws.onmessage = function(e){
                    // json数据转换成js对象
                    let data = eval('(' + e.data + ')')
                    let type = data.type || ''
                    let name = 'Stock' + type.substr(0,1).toUpperCase() + type.substr(1)
                    if (typeof window[name] === "function") {
                        new window[name](data)
                    }else {
                        console.log(data)
                    }
                }
            }

            //心跳检测
            this.heartCheck = {
                timeout:55000,//55秒
                timeoutObj: null,
                reset: function () {
                    if (this.timeoutObj !== null) {
                        clearTimeout(this.timeoutObj)
                        this.timeoutObj = null
                        console.log('断开连接，关闭心跳检测')
                    }
                },
                heart: function () {
                    let self = this
                    this.timeoutObj = setTimeout(function () {
                        _this.ws.send('{"type":"ping"}')
                        this.timeoutObj = setTimeout(self.heart(), this.timeout)
                    }, this.timeout)
                },
                start: function () {
                    if (this.timeoutObj === null) {
                        this.heart()
                        _this.connectTimes++
                        console.info('与服务端连接成功，开启心跳检测')
                    }
                }
            }

            //断线重连
            this.reConnect = {
                timeout:5000,//55秒
                timeoutObj: null,
                reset: function () {
                    if (this.timeoutObj !== null) {
                        clearTimeout(this.timeoutObj)
                        this.timeoutObj = null
                    }
                },
                reCon: function () {
                    let self = this
                    this.timeoutObj = setTimeout(function () {
                        _this.connect()
                        this.timeoutObj = setTimeout(self.reCon(), this.timeout)
                    }, this.timeout)
                },
                start: function () {
                    if (this.timeoutObj === null) {
                        this.reCon()
                        if (_this.connectTimes > 0){
                            console.log('断线重连中···')
                        } else {
                            console.log('连接服务器失败，等待重新连接···')
                        }
                    }
                }
            }
            _this.connect()
        }
    </script>
    <script>
        new createWebSocket('{{config('gateway.push_server_url')}}')
    </script>
    <script>
        function StockConnect (data){
            // 可以放到服务器的onConnect事件中操作，避免client_id泄露
            console.log(data);
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
