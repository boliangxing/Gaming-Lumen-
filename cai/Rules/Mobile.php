<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Rules;

use App\User\Repository\UserCredentialRepository;
use Illuminate\Contracts\Validation\Rule;

class Mobile implements Rule
{
    protected $errorMsg;

    protected $shouldBeUnique;

    public function __construct($shouldBeUnique = true)
    {
        $this->shouldBeUnique = $shouldBeUnique;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $value) != 1) {
            $this->errorMsg = '手机号格式不正确';

            return false;
        }

        if ($this->shouldBeUnique) {
            if ($this->hasRegistered($value, 'mobile')) {
                $this->errorMsg = '该手机号已注册其他账号';

                return false;
            }
        }

        return true;
    }

    /**
     * 该手机号是否已注册其他账号
     *
     * @param $value
     * @param $field
     * @return bool
     */
    protected function hasRegistered($value, $field)
    {
        $userRepository = new UserCredentialRepository();
        return $userRepository->hasEmailOrMobileRegistered($field, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMsg;
    }
}