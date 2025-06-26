<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Operações relacionadas ao usuário autenticado"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Detalhes do usuário autenticado",
     *     description="Retorna os dados do usuário logado, incluindo agendamentos e lembretes.",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('appointments.reminders');
        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user",
     *     summary="Atualizar dados do usuário",
     *     description="Atualiza nome, email ou outras informações permitidas do usuário autenticado.",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Dados atualizados com cusesso."),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json([
            'message' => 'Dados atualizados com cusesso.',
            'user' => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/change-password",
     *     summary="Alterar senha do usuário",
     *     description="Permite que o usuário autenticado altere sua senha.",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChangePasswordRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha alterada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Senha alterada com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Senha atual incorreta",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Senha atual incorreta.")
     *         )
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Senha atual incorreta.'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Senha alterada com sucesso.',
        ]);
    }
}
