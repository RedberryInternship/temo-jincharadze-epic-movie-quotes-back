<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationStatusUpdated implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $notification;

	public function __construct($notification)
	{
		$this->notification = $notification;
	}

	public function broadcastOn()
	{
		return new PrivateChannel('epic-quotes.' . $this->notification['user_id']);
	}

	public function broadcastAs()
	{
		return 'notifications';
	}
}
