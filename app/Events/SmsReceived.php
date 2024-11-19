<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SmsReceived implements ShouldBroadcastNow
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $patientId;
  public $smsMessage;
  public $fromNumber;
  public $toNumber;
  public $direction;
  public $messageBody;
  public $isRead;
  public $isArchived;
  public $createdAt;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($patientId, $smsMessage)
  {
    $this->patientId = $patientId;
    $this->smsMessage = $smsMessage;

   * @param int $patientId
   * @param string $fromNumber
   * @param string $body
   * @param string $toNumber
   * @param int $direction
   * @param bool $isRead
   * @param bool $isArchived
   * @param string $createdAt
   * @return void
   */
  public function __construct($patientId, $fromNumber, $body, $toNumber, $direction, $isRead, $isArchived, $createdAt)
  {
    $this->patientId = $patientId;
    $this->fromNumber = $fromNumber;
    $this->messageBody = $body;
    $this->toNumber = $toNumber;
    $this->direction = $direction;
    $this->isRead = $isRead;
    $this->isArchived = $isArchived;
    $this->createdAt = \Carbon\Carbon::parse($createdAt)->format('Y-m-d H:i:s');
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
      'from_number' => $this->fromNumber,
      'message_body' => $this->messageBody,
      'to_number' => $this->toNumber,
      'direction' => $this->direction,
      'is_read' => $this->isRead,
      'is_archived' => $this->isArchived,
      'created_at' => $this->createdAt,
      'received_at' => now()->toDateTimeString(),
    ];
  }
}
