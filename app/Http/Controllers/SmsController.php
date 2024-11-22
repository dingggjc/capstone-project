<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SmsController extends Controller
{
    public function sendsms()
    {
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_TOKEN");
        $sendernumber = getenv("TWILIO_PHONE");
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create(
            "+63 926 464 8501", // To
            [
                "body" =>
                "chenchen gwapa",
                "from" => $sendernumber
            ]
        );
        dd('Message sent successfully');
    }
}
