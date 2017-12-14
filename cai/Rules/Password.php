<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Rules;

use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[\S]{8,20}$#u', $value) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '必须包含大小写字母和数字';
    }
}