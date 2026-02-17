<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarTagRequest;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __construct(
        private TagService $tag_service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'tags'    => $this->tag_service->listar(),
        ]);
    }

    public function store(GuardarTagRequest $request): JsonResponse
    {
        try {
            $tag = $this->tag_service->guardar($request->validated());

            return response()->json([
                'success' => true,
                'mensaje' => 'Tag creado correctamente.',
                'tag'     => $tag,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => $e->errors()['nombre'][0] ?? 'Error de validación.',
            ], 422);
        }
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $this->tag_service->eliminar($tag);

        return response()->json([
            'success' => true,
            'mensaje' => 'Tag eliminado correctamente.',
        ]);
    }
}
