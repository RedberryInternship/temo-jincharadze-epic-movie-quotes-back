<!DOCTYPE html>
<html lang="en" style="height: 100%">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <style type="text/css">
            u + #body a {
                color: #fff;
                text-decoration: none;
            }

            .body {
                background: linear-gradient(
                    187.16deg,
                    #181623 0.07%,
                    #191725 51.65%,
                    #0d0b14 98.75%
                );
                width: 100%;
                height: 100%;
                min-height: 100%;
            }

            .wrapper {
                padding: 79px 195px;
            }

            .container {
                width: 100%;
                text-align: center;
                margin: 0 auto;
            }

            .img {
                width: 22px;
                height: 20.53px;
            }

            .content-wrapper {
                margin-top: 73px;
            }

            .content {
                text-align: center;
                margin-top: 9px;
            }

            .content_title {
                font-size: 12px;
                font-weight: 500;
                color: #ddccaa !important;
            }

            .url {
                color: #ddccaa !important;
                text-decoration: none;
                margin-top: 24px;
                margin-bottom: 40px;
                font-size: 16px;
                font-weight: 400;
            }

            .button {
                padding: 7px 13px;
                display: block;
                margin-top: 32px;
                margin-bottom: 40px;
                background-color: #e31221;
                border-radius: 4px;
                width: 128px;
                color: #fff !important;
                text-align: center;
                cursor: pointer;
                font-weight: 400;
                font-size: 16px;
                text-decoration: none;
            }

            p {
                color: #fff !important;
                font-size: 16px;
                font-weight: 400;
            }

            .joining {
                margin-top: 24px;
                margin-bottom: 32px;
            }

            .movie {
                margin-top: 24px;
            }

            @media (max-width: 500px) {
                .wrapper {
                    padding: 79px 35px;
                }
                .joining {
                    margin-top: 24px;
                    margin-bottom: 24px;
                }
                .url {
                    margin-top: 16px;
                }
            }
        </style>
    </head>
    <div id="body">
        <div class="body">
            <div class="wrapper">
                <div class="container">
                    <img
                        class="img"
                        src="{{ $message->embed(public_path().'/assets/images/quote.png') }}"
                        alt="quote image"
                    />
                    <div class="content">
                        <h2 class="content_title">MOVIE QUOTES</h2>
                    </div>
                </div>
                <div class="content-wrapper">
                    <p>{{ __("password.hola") }} {{ $user }}!</p>
                    <p class="joining">
                        {{ __("password.joining") }}
                    </p>
                    <a class="button" href="{{ $url }}">
                        {{ __("password.btn") }}</a
                    >
                    <p>
                        {{ __("password.link.description") }}
                    </p>
                    <a href="{{ $url }}" class="url">{{ $url }}</a>
                    <p>
                        {{ __("password.support") }}
                    </p>
                    <p class="movie">MovieQuotes {{ __("password.crew") }}.</p>
                </div>
            </div>
        </div>
    </div>
</html>
