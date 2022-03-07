<?php

namespace App\Http\Requests;

use App\Enums\BankCartPrefixEnum;
use App\Rules\DestinationNumberLengthRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:10000'],
            'description' => ['required', 'string', 'max:30'],
            'destinationFirstname' => ['required', 'string', 'max:30'],
            'destinationLastname' => ['required', 'string', 'max:30'],
            'destinationNumber' => ['required', 'string', new DestinationNumberLengthRule()],
            'deposit' => ['nullable','string', 'max:26', Rule::requiredIf($this->isDepositRequired())],
            'secondPassword' => ['nullable','string', Rule::requiredIf($this->isSecondPasswordRequired())],

            'paymentNumber' => ['nullable','string', 'max:30', Rule::requiredIf($this->isPaymentNumberRequired())],
            'reasonDescription' => ['nullable','numeric', Rule::requiredIf($this->isReasonDescriptionRequired())],

        ];
    }

    /**
     * @return bool
     */
    private function isDepositRequired(): bool
    {
        if (strlen($this->destinationNumber) == 16) {
            return is_numeric(strpos($this->destinationNumber, BankCartPrefixEnum::Keshavarzi->value));
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isSecondPasswordRequired(): bool
    {
        if (strlen($this->destinationNumber) == 16) {
            return is_numeric(strpos($this->destinationNumber, BankCartPrefixEnum::Parsian->value));
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isPaymentNumberRequired(): bool
    {
        if (strlen($this->destinationNumber) == 16) {
            return is_numeric(strpos($this->destinationNumber, BankCartPrefixEnum::Ayandeh->value));
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isReasonDescriptionRequired(): bool
    {
        if (strlen($this->destinationNumber) == 16) {
            return is_numeric(strpos($this->destinationNumber, BankCartPrefixEnum::Ayandeh->value));
        }
        return false;
    }
}


