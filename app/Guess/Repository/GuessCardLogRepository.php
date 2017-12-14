<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Repository;

use Cai\Foundation\Repository;

class GuessCardLogRepository extends Repository
{
    protected $_connection = 'guess';

    protected $_table = 'guess_card_logs';
}