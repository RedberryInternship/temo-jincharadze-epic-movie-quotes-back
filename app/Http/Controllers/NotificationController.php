<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$notifications = Notification::where('user_id', auth()->user()->id)->with(['sender', 'quote'])->orderBy('created_at', 'desc')->get();

		return response()->json($notifications, 200);
	}

	public function read(): JsonResponse
	{
		if (request('markAllAsRead'))
		{
			$notifications = Notification::where('has_new', true)->get();

			foreach ($notifications as $notification)
			{
				$notification->update([
					'has_new' => false,
				]);
			}
		}

		$readNotification = Notification::where('quote_id', request('quote_id'))->where('user_id', auth()->user()->id)->where('has_new', true)->first();

		if ($readNotification)
		{
			$readNotification->update(['has_new' => false]);
		}

		return response()->json($readNotification, 200);
	}
}
