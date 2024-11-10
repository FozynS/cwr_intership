<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SmsReceived implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $patientId;
  public $smsMessage;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($patientId, $smsMessage)
  {
    $this->patientId = $patientId;
    $this->smsMessage = $smsMessage;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new Channel('patient.' . $this->patientId);
  }

  public function broadcastWith()
  {
    return [
      'from_number' => $this->smsMessage->from_number,
      'body' => $this->smsMessage->message_body,
      'received_at' => $this->smsMessage->created_at
    ];
  }
}
