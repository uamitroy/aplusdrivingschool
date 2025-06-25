<!DOCTYPE html>
<html lang="en-GB"> 
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="{!! asset('uploads/'.$setting->favicon) !!}">
<title>Page not found | {{ $setting->title }}</title>
<meta name="description" content="Sorry - Page not found. Please visit home page." />
<meta name="keywords" content="404, page not found" />
<meta name="robots" content="no index, no follow" />
<link rel="canonical" href="{{ url ('/404') }}"/>
<meta property="og:locale" content="en_GB" />
<meta property="og:type" content="website"/>
<meta property="og:image" content="{!! asset('design/images/404.jpg') !!}" />
<meta property="og:title" content="Page not found | {{ $setting->title }}"  />
<meta property="og:description" content="Sorry - Page not found. Please visit home page." />
<meta property="og:url" content="{{ url ('/404') }}"/>
<meta property="og:site_name" content="{{ $setting->title }}" />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,800' rel='stylesheet' type='text/css'>



@stack('css')
<style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
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
                font-size: 12px;
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
             <div class="content">
                <div class="title m-b-md">
                    Sorry, 404 Not Found
                </div>
                <div class="links">
                    <a href="{{ url('/') }}">Back To Home</a>
                </div>
            </div>
        </div>

@stack('js')
</body>
</html>


