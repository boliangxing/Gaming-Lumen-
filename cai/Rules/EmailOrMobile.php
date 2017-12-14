<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Rules;

use App\User\Repository\UserCredentialRepository;
use Illuminate\Contracts\Validation\Rule;

/**
 * 邮箱或昵称验证
 *
 * @package Cai\Rules
 */
class EmailOrMobile implements Rule
{
    protected $errorMsg;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (strpos($value, '@') !== false) {
            if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                $this->errorMsg = '邮箱格式不正确';

                return false;
            }

            if ($this->hasRegistered($value, 'email')) {
                $this->errorMsg = '该邮箱已注册其他账号';

                return false;
            }
        } else {
            if (preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $value) != 1) {
                $this->errorMsg = '手机号格式不正确';

                return false;
            }

            if ($this->hasRegistered($value, 'mobile')) {
                $this->errorMsg = '该手机号已注册其他账号';

                return false;
            }
        }

        return true;
    }

    /**
     * 该手机号或邮箱是否已注册其他账号
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