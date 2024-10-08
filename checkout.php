<?php
require_once __DIR__ . "/vendor/autoload.php";
$stripe_secret_key = "PRIVATE_KEY";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$bill_id = $_GET['bill_id'];
$amount = $_GET['amount'];

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost/hotelmanagementsystem/success.php",
    "cancel_url" => "http://localhost/hotelmanagementsystem/customer.php",
    "payment_method_types" => ["card"],
    "line_items" => [
        [
            "price_data" => [
                "currency" => "usd",
                "unit_amount" => $amount * 100, // Amount in cents
                "product_data" => [
                    "name" => "Bill Payment",
                ],
            ],
            "quantity" => 1,
        ],
    ],
]);

http_response_code(303);
header("Location:" . $checkout_session->url);
