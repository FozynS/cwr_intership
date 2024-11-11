<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use Illuminate\Support\Facades\Log;

class SmsReceived implements ShouldBroadcastNow
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $patientId;
  public $fromNumber;
  public $body;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($patientId, $fromNumber, $body)
  {
    $this->patientId = $patientId;
    $this->fromNumber = $fromNumber;
    $this->body = $body;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
    return new PrivateChannel('patient.'.$this->patientId);
  }

  public function broadcastWith()
  {
    return [
      'from_number' => $this->fromNumber,
      'body' => $this->body,
      'received_at' => now()->toDateTimeString(), 
    ];
  }
}
