<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Str;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return TransactionCollection
     */
    public function index()
    {
        return new TransactionCollection(
            Auth::user()->transactions()->with('detail')
                ->orderBy('id', 'desc')
                ->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TransactionRequest $request
     * @return JsonResponse
     */
    public function store(TransactionRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();
        $trackId = Str::uuid();
        $clientId = 'client_id';
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJy9KQyZXNoVG9rZW4iOiJVNWx0eGV1M3BuYm90UERHOXFQRlVFeTFoTGg5WmVmTHJNb2tQS29qc1AxajBjU0JQNEJLOFZJNkZVNjVzRGNTbjdwVk96UWFQWkh3Um9EVkk4cWNOMkE1NWxvODNSYXlWZncwWEdSTHloUWxXY2VwTE9vWHJ3VjJhMVhIUFZNeHhWYjdIZzJVWDQ1WURIaElkVTlGNjBiQThGeENhZzI0WXBHd2pCSE1jSVJwUjFXM052dTdyWERmUnc4blJ4OUVpaDQ1SzNDYnZwbnVERU5oY3BFQ1p0aEZuMnZHbGJiZThnRVF0Z1djZUNYZVRCeFhRQ1lpRThwMVZjWXNOSVI1IiwiY3JlYXRpb25EYXRlIjoiMTM5NzA3MzAxMTEzNTUiLCJsaWZlVGltZSI6ODY0MDAwMDAwLCJjbGllbnRJZCI6IjU4YTE3YzA3MzZhNjBlNWM2NTI3NGM2OCIsInVzZXJJZCI6IjAxMjM0NTY3ODkiLCJhY3RpdmUiOnRydWUsInNjb3BlcyI6WyJvYWs6ZGVwb3NpdC10by1pYmFuOmdldCJdLCJkZXBvc2l0cyI6W10sIm1vbnRobHlDYWxsTGltaXRhdGlvbiI6bnVsbCwibWF4QW1vdW50UGVyVHJhbnNhY3Rpb24iOm51bGwsImRlc3RpbmF0aW9uIjpudWxsLCJ0eXBlIjoiQ0xJRU5ULUNSRURFTlRJQUwiLCJpYXQiOjE1NDAxOTQyMzV9.ZplbUfe2De7r8RqpbyZ8Pbbf3lqzHoGi0esTXBJM6tM";

        $response = Http::withHeaders([
            "Authorization" => "Bearer {$token}",
            "accept" => "application/json",
            "Content-Type" => "application/json",
        ])
            ->post("https://apibeta.finnotech.ir/oak/v2/clients/{$clientId}/transferTo?trackId={$trackId}", [
            "amount" => $data['amount'],
            "description" => $data['description'],
            "destinationFirstname" => $data['destinationFirstname'],
            "destinationLastname" => $data['destinationLastname'],
            // cart numbser or saba numbser
            "destinationNumber" => $data['destinationNumber'],
            //account number if multi account is field used
            "deposit" => $data['deposit'] ?? null,
            "sourceFirstName" => $user->firstname,
            "sourceLastName" => $user->lastname,
            //
            "paymentNumber" => $data['paymentNumber'] ?? null,
            "reasonDescription" => $data['reasonDescription'] ?? null,
        ])
            ->body();

        $response = json_decode($response);

        $transaction = $user->transactions()->create([
            "amount" => $data['amount'],
            "description" => $data['description'],
            "destinationFirstname" => $data['destinationFirstname'],
            "destinationLastname" => $data['destinationLastname'],
            // cart numbser or saba numbser
            "destinationNumber" => $data['destinationNumber'],

            "deposit" => $data['deposit'] ?? null,
            "sourceFirstName" => $response->result->sourceFirstName ?? $user->firstname,
            "sourceLastName" => $response->result->sourceLastName ?? $user->lastname,

            "reasonDescription" => $data['reasonDescription'] ?? null,

            'message' => $response->result->message ?? $response->error->message,
            "status" => $response->status,
            "trackId" => $response->trackId,
        ]);

        if ($response->status === TransactionStatusEnum::Success->value) {
            $transaction->detail()->create([
                //account number
                "destinationNumber" => $response->result->destinationNumber,

                "sourceNumber" => $response->result->sourceNumber,

                "type" => $response->result->type,

                'inquiryDate' => $response->result->inquiryDate,
                'inquiryTime' => $response->result->inquiryTime,
                'refCode' => $response->result->refCode,
                'paymentNumber' => $response->result->paymentNumber,
            ]);

            return new JsonResponse([
                'message' => 'تراکنش با موفقیت انجام شد',
            ], Response::HTTP_CREATED);
        }

        return new JsonResponse([
            'message' => 'تراکنش با مشکل مواجه شد',
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     * @return TransactionResource
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction->load('detail'));
    }
}
