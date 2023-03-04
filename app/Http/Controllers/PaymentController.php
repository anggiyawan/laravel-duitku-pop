<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function Checkout(Request $request) {

        $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001");
        // false for production mode
        // true for sandbox mode
        $duitkuConfig->setSandboxMode(true);
        // set sanitizer (default : true)
        $duitkuConfig->setSanitizedMode(false);
        // set log parameter (default : true)
        $duitkuConfig->setDuitkuLogs(true);

        // $paymentMethod      = ""; // PaymentMethod list => https://docs.duitku.com/pop/id/#payment-method
        $paymentAmount      = 10000; // Amount
        $email              = "customer@gmail.com"; // your customer email
        $phoneNumber        = "081234567890"; // your customer phone number (optional)
        $productDetails     = "Test Payment";
        $merchantOrderId    = (string)time(); // from merchant, unique   
        $additionalParam    = ''; // optional
        $merchantUserInfo   = ''; // optional
        $customerVaName     = 'John Doe'; // display name on bank confirmation display
        $callbackUrl        = 'http://YOUR_SERVER/callback'; // url for callback
        $returnUrl          = 'http://YOUR_SERVER/return'; // url for redirect
        $expiryPeriod       = 60; // set the expired time in minutes

        // Customer Detail
        $firstName          = "John";
        $lastName           = "Doe";

        // Address
        $alamat             = "Jl. Kembangan Raya";
        $city               = "Jakarta";
        $postalCode         = "11530";
        $countryCode        = "ID";

        $address = array(
            'firstName'     => $firstName,
            'lastName'      => $lastName,
            'address'       => $alamat,
            'city'          => $city,
            'postalCode'    => $postalCode,
            'phone'         => $phoneNumber,
            'countryCode'   => $countryCode
        );

        $customerDetail = array(
            'firstName'         => $firstName,
            'lastName'          => $lastName,
            'email'             => $email,
            'phoneNumber'       => $phoneNumber,
            'billingAddress'    => $address,
            'shippingAddress'   => $address
        );

        // Item Details
        $item1 = array(
            'name'      => $productDetails,
            'price'     => $paymentAmount,
            'quantity'  => 1
        );

        $itemDetails = array(
            $item1
        );

        $params = array(
            'paymentAmount'     => $paymentAmount,
            'merchantOrderId'   => $merchantOrderId,
            'productDetails'    => $productDetails,
            'additionalParam'   => $additionalParam,
            'merchantUserInfo'  => $merchantUserInfo,
            'customerVaName'    => $customerVaName,
            'email'             => $email,
            'phoneNumber'       => $phoneNumber,
            'itemDetails'       => $itemDetails,
            'customerDetail'    => $customerDetail,
            'callbackUrl'       => $callbackUrl,
            'returnUrl'         => $returnUrl,
            'expiryPeriod'      => $expiryPeriod
        );

        try {
            // createInvoice Request
            $responseDuitkuPop = \Duitku\Pop::createInvoice($params, $duitkuConfig);

            header('Content-Type: application/json');
            echo $responseDuitkuPop;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public function Notification(){
        $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001");
        // false for production mode
        // true for sandbox mode
        $duitkuConfig->setSandboxMode(true);
        // set sanitizer (default : true)
        $duitkuConfig->setSanitizedMode(false);
        // set log parameter (default : true)
        $duitkuConfig->setDuitkuLogs(true);

        try {
            $callback = \Duitku\Pop::callback($duitkuConfig);
        
            header('Content-Type: application/json');
            $notif = json_decode($callback);
        
            // var_dump($callback);
        
            if ($notif->resultCode == "00") {
                // Action Success
                echo "Thanks You";
            } else if ($notif->resultCode == "01") {
                // Action Failed
                echo "Failed";
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }
}
