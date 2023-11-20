<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NestedArrays implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value) && !count($value) === 2) {
            $fail('Vous devez intégrer 2 valeurs à :attribute.');

        } elseif (is_array($value) && count($value) === 2) {
            foreach ($value as $item) {
                if (!is_array($item)) {
                    $fail('Vous devez intégrer 2 valeurs au champ :attribute.');
                }
            }
        }
    }
}
