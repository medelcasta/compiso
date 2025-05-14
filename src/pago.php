<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PayPal JS SDK Standard Integration</title>
    </head>
    <body>
        <div id="paypal-button-container"></div>
        <p id="result-message"></p>

       
        <!-- Initialize the JS-SDK -->
        <script
            src="https://www.paypal.com/sdk/js?client-id=ASSP_e4MTUSMoD_Wle0o74fgTWuD86qjpu9ORevRrveZsp_5nPGl83QfFzWPRQmTgx6Bg8eG7h2M9RqX&buyer-country=FR&currency=EUR&components=buttons&disable-funding=venmo,paylater,card"
            data-sdk-integration-source="developer-studio"
        ></script>
        <script src="app.js"></script>
       
    </body>
</html>