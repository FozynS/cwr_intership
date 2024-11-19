<?php

namespace App\Listeners;

use App\Events\SmsReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSmsToWebSocket implements ShouldQueue
{
  use InteractsWithQueue;

  public function handle(SmsReceived $event)
  {
    Log::info("Message received: ", [
      'patient_id' => $event->patientId,
      'from_number' => $event->fromNumber,
      'body' => $event->body,
    ]);
  }
}
