<?php

namespace App\Rules;

use App\Enums\BankCartPrefixEnum;
use Illuminate\Contracts\Validation\Rule;

class DestinationNumberLengthRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen($value) == 16 || strlen($value) == 26;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The destination number length must be 16 or 26';
    }


}
