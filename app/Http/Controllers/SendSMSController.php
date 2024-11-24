<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Api\SmsApi;

class SendSMSController extends Controller
{
    public function index()
    {
        return Inertia::render('SendSms');
    }

    public function sendSMS(Request $request)
    {
        $request->validate([
            'number' => 'required|regex:/^\+?[1-9]\d{1,14}$/', // Validate phone number
        ]);

        $configuration = new Configuration(
            host: env('INFOBIP_HOST'),
            apiKey: env('INFOBIP_API_KEY')
        );

        $sendSmsApi = new SmsApi(config: $configuration);

        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: $request->number)
            ],
            from: 'InfoSMS',
            text: 'Maona Ohabs man kibot'
        );

        $requestBody = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessage($requestBody);
            return response()->json(['success' => 'SMS sent successfully!']);
        } catch (ApiException $apiException) {
            return response()->json(['error' => $apiException->getMessage()], 500);
        }
    }
}
