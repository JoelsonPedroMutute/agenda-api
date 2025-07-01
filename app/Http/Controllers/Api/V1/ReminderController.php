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
use OpenApi\Annotations as OA;


class ReminderController extends Controller
{
    protected ReminderService $service;

    public function __construct(ReminderService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

  
    public function index(Request $request, ReminderFilter $filter)
    {
        $user = Auth::user();

        $reminders = $user->role === 'admin'
            ? $this->service->getAllAdmin($filter, $request->per_page ?? 10)
            : $this->service->getAll($user->id, $filter, $request->per_page ?? 10);

        return ReminderResource::collection($reminders);
    }

   
    public function store(StoreReminderRequest $request)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->createAdmin($request->validated())
            : $this->service->create($user->id, $request->validated());

        return new ReminderResource($reminder);
    }

   
    public function show($id)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->findAdmin($id)
            : $this->service->find($user->id, $id);

        return new ReminderResource($reminder);
    }

  
    public function update(UpdateReminderRequest $request, $id)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->updateAdmin($id, $request->validated())
            : $this->service->update($user->id, $id, $request->validated());

        return new ReminderResource($reminder);
    }

   
    public function destroy($id)
    {
        $user = Auth::user();

        $user->role === 'admin'
            ? $this->service->deleteAdmin($id)
            : $this->service->delete($user->id, $id);

        return response()->json(['message' => 'Lembrete excluído com sucesso.']);
    }
}
