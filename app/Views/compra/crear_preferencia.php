<?php
require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken("APP_USR-35ce4733-d538-4506-a6a5-7e4defe68c24");

$preference = new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->title = "Plan Premium EcoVolt";
$item->quantity = 1;
$item->unit_price = 85.00;
$preference->items = array($item);

$preference->save();

echo json_encode(["id" => $preference->id]);
?>
