<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReminderResource::collection(
            Reminder::with('appointment.user')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'appointment_id' => 'required|exists:appointments,id',
        'remind_at'      => 'required|date',
        'method'         => 'required|string|in:email,sms,notificação',
    ]);

    $reminder = Reminder::create($data);

    return response()->json([
        'message' => 'Reminder criado com sucesso.',
        'data' => new ReminderResource($reminder->load('appointment.user'))
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder)
    {
        return new ReminderResource($reminder->load('appointment.user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reminder $reminder)
    {
        $reminder->update($request->only([
            'message', 'method', 'notification_time'
        ]));
        return new ReminderResource($reminder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return response()->json([
            'message' => 'Reminder deletadoo com sucesso'
        ]);
    }
}
