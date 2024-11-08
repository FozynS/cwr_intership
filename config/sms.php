<?php

return [
  'twilio' => [
    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'from' => env('TWILIO_FROM'),
  ],
  'company_number' => env('TWILIO_FROM'),
];