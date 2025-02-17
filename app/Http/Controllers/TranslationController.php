<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\TranslationRequest;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Translation API Documentation",
 *     description="API documentation for the Translation system",
 *     @OA\Contact(
 *         email="ashirbutt925@gmail.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 */
class TranslationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/translations",
     *     summary="Get list of translations",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $translations = Translation::filter($request->all())->paginate(50);
        return response()->json($translations);
    }

    /**
     * @OA\Post(
     *     path="/api/translations",
     *     summary="Create a new translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "content", "tag"},
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="key_1234"),
     *             @OA\Property(property="content", type="string", example="Hello World"),
     *             @OA\Property(property="tag", type="string", example="web")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Translation added",
     *         @OA\Header(
     *             header="Accept",
     *             description="Accept header",
     *             @OA\Schema(type="string", default="application/json")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(TranslationRequest $request): JsonResponse
    {
        $translation = Translation::create($request->validated());
        return response()->json(['message' => 'Translation added', 'data' => $translation], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/translations/{translation}",
     *     summary="Update an existing translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="translation",
     *         in="path",
     *         description="Translation ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "content", "tag"},
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="key_1234"),
     *             @OA\Property(property="content", type="string", example="Hello World"),
     *             @OA\Property(property="tag", type="string", example="web")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation updated",
     *         @OA\Header(
     *             header="Accept",
     *             description="Accept header",
     *             @OA\Schema(type="string", default="application/json")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(TranslationRequest $request, Translation $translation): JsonResponse
    {
        $translation->update($request->validated());
        return response()->json(['message' => 'Translation updated', 'data' => $translation]);
    }

    /**
     * @OA\Get(
     *     path="/api/translations/export",
     *     summary="Export translations",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Header(
     *             header="Accept",
     *             description="Accept header",
     *             @OA\Schema(type="string", default="application/json")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function export(): JsonResponse
    {
        $translations = Cache::remember('translations_json', 300, function () {
            return Translation::all()->groupBy('locale')->map(fn($items) => $items->pluck('content', 'key'));
        });

        return response()->json($translations);
    }
}
