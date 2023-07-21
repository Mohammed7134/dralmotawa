<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomRule implements Rule
{

    private $count;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $count)
    {
        $this->count = $count;
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
        return (is_numeric($value) || mb_strlen($value) > $this->count) && !str_contains($value, ")") && !str_contains($value, "(");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "كلمة البحث يجب أن تكون أكبر من {$this->count} حروف وخالية من الرموز.";
    }
}
