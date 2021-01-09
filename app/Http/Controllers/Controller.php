<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function successResponse(array $data = []): JsonResponse
    {
        $data['success'] = true;

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse($message, $code);
    }

    /**
     * @return JsonResponse
     */
    protected function generalErrorResponse(): JsonResponse
    {
        return new JsonResponse(
            'Something went wrong, please contact developers.',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
