<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,

            'destinationFirstname' => $this->destinationFirstname,
            'destinationLastname' => $this->destinationLastname,
            'destinationNumber' => $this->detail->destinationNumber ?? $this->destinationNumber,

            'sourceFirstName' => $this->sourceFirstName,
            'sourceLastName' => $this->sourceLastName,
            'sourceNumber' => $this->detail->sourceNumber ?? $this->deposit,

            'reasonDescription' => $this->reasonDescription,
            'status' => $this->status,
            'trackId' => $this->trackId,

            'created_at' => $this->created_at,
        ];
    }
}
