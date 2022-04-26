<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tailwind</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://api.jawg.io/libraries/jawg-places@latest/jawg-places.js?access-token=eCL1lLYmmPrpc53HcET5e7f52JRCEJM5izJTVtpvJ81Vh4Yld3n4IiGcVqwY1op9"></script>
    </head>
    <body class="bg-blue-200">
        <div id="app" class="flex justify-center pt-16">
            <weather-component></weather-component>
        </div>
    </body>
</html>
