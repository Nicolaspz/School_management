<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DataNiver implements Rule
{
    /**
     * Verifica se a regra de validação passa.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $birthdate = Carbon::parse($value);

        // A data para ter exatamente 16 anos completos, o que é o máximo permitido.
        $sixteenYearsAgo = Carbon::now()->subYears(16)->startOfDay();

        // A data para ter exatamente 15 anos completos, o que é o mínimo permitido.
        $fifteenYearsAgo = Carbon::now()->subYears(15)->startOfDay();

        // A pessoa deve ter mais de 15 anos e menos de 17 anos
        return $birthdate->isAfter($sixteenYearsAgo) && $birthdate->isBefore($fifteenYearsAgo);
    }

    /**
     * Retorna a mensagem de erro de validação.
     *
     * @return string
     */
    public function message()
    {
        return 'A idade do estudante deve estar entre 15 e 16 anos.';
    }
}
