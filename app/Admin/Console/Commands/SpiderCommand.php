<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/23
 * Time: 18:06
 */
namespace App\Admin\Console\Commands;

use App\Admin\Logic\DataSpider;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SpiderCommand extends Command
{
    protected $name = 'spider';

    protected $description = '';

    public function handle()
    {
        //DataSpider::crawlerScheduleList();
        //DataSpider::crawlerPlayerList();
        DataSpider::crawlerTeamList();
    }

    public function getOptions()
    {
        return [
            ['op','op',InputOption::VALUE_OPTIONAL,'',null]
        ];
    }
}