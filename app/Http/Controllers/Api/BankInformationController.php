<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankInformationRequest;
use App\Http\Resources\BankInformationCollection;
use App\Http\Resources\BankInformationResource;
use App\Models\BankInformation;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BankInformationCollection
     */
    public function index()
    {
        return new BankInformationCollection(Auth::user()->bankInformations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BankInformationRequest $request
     * @return JsonResponse
     */
    public function store(BankInformationRequest $request)
    {
        $data = $request->validated();

        $request->user()->bankInformations()->create($data);

        return new JsonResponse(['message' => 'Bank information created successfully'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param BankInformation $bankInformation
     * @return BankInformationResource
     */
    public function show(BankInformation $bankInformation)
    {
        return new BankInformationResource($bankInformation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param BankInformation $bankInformation
     * @return Response
     */
    public function update(Request $request, BankInformation $bankInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BankInformation $bankInformation
     * @return JsonResponse
     */
    public function destroy(BankInformation $bankInformation)
    {
        $bankInformation->delete();

        return new JsonResponse(['message' => 'Bank information deleted successfully'], Response::HTTP_OK);
    }
}
