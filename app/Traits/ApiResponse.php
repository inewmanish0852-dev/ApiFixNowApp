<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    // ── Success ───────────────────────────────────────────

    protected function success(
        mixed $data = null,
        string $message = 'Request successful.',
        int $status = 200
    ): JsonResponse {
        $response = ['success' => true, 'message' => $message];

        if (! is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    protected function created(mixed $data = null, string $message = 'Resource created.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function paginated($paginator, string $message = 'Request successful.'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ], 200);
    }

    // ── Error ─────────────────────────────────────────────

    protected function error(
        string $message = 'Something went wrong.',
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        $response = ['success' => false, 'message' => $message];

        if (! is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    protected function validationError(mixed $errors): JsonResponse
    {
        return $this->error('Validation failed.', 422, $errors);
    }

    protected function notFound(string $message = 'Resource not found.'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function unauthorized(string $message = 'Unauthenticated.'): JsonResponse
    {
        return $this->error($message, 401);
    }

    protected function forbidden(string $message = 'Access denied.'): JsonResponse
    {
        return $this->error($message, 403);
    }

    protected function serverError(string $message = 'Internal server error.'): JsonResponse
    {
        return $this->error($message, 500);
    }
}