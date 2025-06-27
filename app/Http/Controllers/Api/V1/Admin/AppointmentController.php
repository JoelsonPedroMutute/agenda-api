<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AppointmentResource::collection(
            Appointment::with('user', 'reminders')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $data = $request->validate([
        'user_id'    => 'required|exists:users,id',
        'title'      => 'required|string|max:255',
        'description'=> 'nullable|string',
        'date'       => 'required|date',
        'start_time' => 'required',
        'end_time'   => 'required|after_or_equal:start_time',
    ]);

    $appointment = Appointment::create($data);

    return response()->json([
        'message' => 'Appointment created successfully.',
        'data' => new AppointmentResource($appointment)
    ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load('user', 'reminders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->only([
            'title', 'description', 'date', 'start_time', 'end_time', 'status'
        ]));
        return new AppointmentResource($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        response()->json([
            'message' => 'Appointment deletado com cucesso'
        ]);
    }
}
