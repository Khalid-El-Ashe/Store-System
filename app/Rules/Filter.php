<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Filter implements ValidationRule
{

    protected array $blackList;
    public function __construct($blackList)
    {
        $this->blackList =  $blackList;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        ! in_array(strtolower($value), $this->blackList);
    }
}
