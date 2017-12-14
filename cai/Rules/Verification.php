<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Rules;

use Illuminate\Contracts\Validation\Rule;

class Verification implements Rule
{
    protected $errMsg;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false || $value < 1000 || $value > 9999) {
            $this->errMsg = '验证码格式不正确';

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errMsg;
    }
}