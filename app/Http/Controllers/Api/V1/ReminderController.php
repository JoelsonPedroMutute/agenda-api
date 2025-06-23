<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Services\ReminderService;
use App\Filters\ReminderFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    protected $service;

    public function __construct(ReminderService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
public function index(Request $request, ReminderFilter $filter)
{
    $reminders = $this->service->getAll(Auth::id(), $filter, $request->per_page ?? 10);
    return ReminderResource::collection($reminders);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReminderRequest $request)
    {
        $reminder = $this->service->create(Auth::id(), $request->validated());
       return new ReminderResource($reminder);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $reminder = $this->service->find(Auth::id(), $id);
        return new ReminderResource($reminder);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateReminderRequest $request, $id)
{
    $reminder = $this->service->update(Auth::id(), (int) $id, $request->validated());
    return new ReminderResource($reminder);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete(Auth::id(), $id);
        return response()->json([
            'Lembrete excluído com sucesso'
        ], 204);
    }
}
