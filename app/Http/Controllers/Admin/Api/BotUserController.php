<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Services\BotUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BotUserController extends Controller
{
    protected BotUserService $botUserService;

    public function __construct(BotUserService $botUserService)
    {
        $this->botUserService = $botUserService;
    }

    public function index(Request $request): JsonResponse
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $phone = $request->input('phone');

        $query = BotUser::query()->orderBy('id', 'asc');

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($phone) {
            $query->where('phone', 'LIKE', "%$phone%");
        }

        $botUsers = $query->get();

        return response()->json($botUsers);
    }

    public function isActive(Request $request, BotUser $botUser): JsonResponse
    {
        $validated = $request->validate(['isactive' => 'required|boolean']);

        $this->botUserService->update($botUser, $validated);

        return response()->json([], 200);
    }
}
