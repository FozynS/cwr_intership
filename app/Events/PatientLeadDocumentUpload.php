<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PatientLeadDocumentUpload
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $document;

    /**
     * PatientLeadDocumentUpload constructor.
     * @param $document
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}