<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Repository;

use Cai\Foundation\Repository;

class GuessCaiLogRepository extends Repository
{
    protected $_connection = 'guess';

    protected $_table = 'guess_cai_logs';

    const STATUS_NORMAL = 1;
    const STATUS_CANCELLED = 2;
}