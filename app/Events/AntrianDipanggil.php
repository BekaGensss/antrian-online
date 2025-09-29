<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Antrian;

class AntrianDipanggil implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $antrian;

    public function __construct(Antrian $antrian)
    {
        // Muat relasi loket agar tersedia di front-end
        $this->antrian = $antrian->load('loket'); 
    }

    public function broadcastOn(): Channel
    {
        // Channel yang akan didengarkan oleh monitor
        return new Channel('antrian-channel');
    }

    public function broadcastAs(): string
    {
        // Nama event di sisi JavaScript
        return 'antrian.dipanggil'; 
    }
}