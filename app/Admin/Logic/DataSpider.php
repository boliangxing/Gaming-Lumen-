<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/23
 * Time: 17:37
 */
namespace App\Admin\Logic;

use App\Admin\Repository\Crawler\LeagueRepository;
use App\Admin\Repository\Crawler\PlayerRepository;
use App\Admin\Repository\Crawler\ScheduleRepository;
use App\Admin\Repository\Crawler\TeamRepository;
use GuzzleHttp\Client;

class DataSpider
{
    public static function crawlerScheduleList()
    {
        $client = new Client();
        $url = 'http://lol.66esports.cn/zh-CN/Match/GetResults?pageIndex={page}';
        $max = 133;
        $current = 1;
        $schedule_data = [];
        $league_data = [];
        while($current<=$max) {
            $response = $client->request('GET', str_replace('{page}',$current,$url),[]);
            if($response->getStatusCode()==200) {
                $result = json_decode($response->getBody()->getContents(),true);
                foreach($result['Data'] as $row){
                    $schedule_data[] = [
                        '66esports_id'=>$row['Id'],
                        'a_team_id'=>$row['TeamA']['TeamId'],
                        'b_team_id'=>$row['TeamB']['TeamId'],
                        'league_id'=>$row['LeagueId'],
                        'game_id'=>1,
                        'result'=>$row['PredictAvsB'],
                        'start_time'=>substr($row['StartTime'],6,10)
                    ];
                    if(!isset($league_data[$row['LeagueId']])){
                        $league_data[$row['LeagueId']] = [
                            '66esports_id'=>$row['LeagueId'],
                            'league_name'=>$row['League'],
                            'league_logo'=>$row['LeagueLogo'],
                            'game_id'=>1
                        ];
                    }
                }
            }

            $current++;
            echo $current . PHP_EOL;
        }
        //(new LeagueRepository())->insertAll($league_data);
        (new ScheduleRepository())->insertAll($schedule_data);
        //print_r($league_data);
    }

    public static function crawlerPlayerList()
    {
        $client = new Client();
        $url = 'http://lol.66esports.cn/zh-CN/Player/GetPlayerList?roleId=-1&season=-1&playerName=';
        $response = $client->request('GET',$url,[]);
        $result = json_decode($response->getBody()->getContents(),true);
        $player_data = [];
        foreach($result['Data'] as $row){
            $player = $row['PlayerBasicInfo'];
            $player_data[] = [
                'game_id'=>1,
                'player_name'=>$player['Name'],
                'player_logo'=>$player['HeadImg'],
                'team_id'=>$player['TeamId'],
                '66esports_id'=>$player['Id'],
                'region_id'=>$row['RegionId']
            ];
        }
        (new PlayerRepository())->insertAll($player_data);
    }

    public static function crawlerTeamList()
    {
        $client = new Client();
        $url = 'http://lol.66esports.cn/zh-CN/Team/GetTeamRankingList?season=-1&teamName=';
        $response = $client->request('GET',$url,[]);
        $result = json_decode($response->getBody()->getContents(),true);
        $team_data = [];
        foreach($result['Data'] as $row){
            $team = $row['TeamInfo'];
            $team_data[] = [
                'game_id'=>1,
                'team_name'=>$team['Name'],
                'team_logo'=>$team['Logo'],
                'team_short_name'=>$team['Code'],
                '66esports_id'=>$team['Id'],
                'region_id'=>$team['Region']['ParentId']
            ];
        }
        (new TeamRepository())->insertAll($team_data);
    }
}