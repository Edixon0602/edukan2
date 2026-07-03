<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'destinatario' => ['required', 'in:todos,individual'],
            'user_id' => ['required_if:destinatario,individual', 'nullable', 'exists:users,id'],
            'type' => ['required', 'in:info,success,warning'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        if ($request->destinatario === 'todos') {
            // Mass broadcast (stores with user_id = null)
            Notification::create([
                'user_id' => null,
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
            ]);
        } else {
            // Targeted alert
            Notification::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Alerta enviada y distribuida al sistema.'
        ]);
    }

    public function destroy(Notification $notification)
    {
        // Access protection: Admin or the owner
        if (auth()->user()->isAdmin() || auth()->id() === $notification->user_id) {
            $notification->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Notificación eliminada.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No autorizado.'
        ], 403);
    }
}
