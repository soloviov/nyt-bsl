<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\NYTimes;
use App\Http\Requests\GetHistory;
use Illuminate\Http\JsonResponse;

class HistoryController extends Controller
{
    public function __construct(protected NYTimes $NYTimeService)
    {
    }

    public function index(GetHistory $request): JsonResponse
    {
        $dto = $request->dto();
        try {
            $data = $this->NYTimeService->getHistory($dto);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }
}
