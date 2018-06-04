<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ServersStatistics extends Model
{
    /**
     * Insert the new servers statistics to the database
     *
     * @param array $serversRawStatistics
     * @return bool
     */
    public static function insertToTheDb(array $serversRawStatistics)
    {

        // Check the existing servers statistics and get the servers id
        $serversStatisticsInTheDb = ServersStatistics::all()->toArray();

        // Contains the exist servers
        $serversList = [];

        $serversListInTheDb = Servers::all();

        // Remap the servers
        foreach ($serversListInTheDb as $server) {
            $serverId = $server->id;
            $serverName = $server->server_name;
            $serversList[$serverName] = ['serverId' => $serverId, 'serverName' => $serverName];
        }

        // Make an array and fill with the statistics date
        $serversStatistics = [];
        foreach ($serversStatisticsInTheDb as $result) {

            $serverId = $result['servers_statistics_server_id'];
            $serverStatisticDate = $result['servers_statistics_date'];

            if (empty($serversStatistics[$serverId])) {
                $serversStatistics[$serverId] = array($serverStatisticDate);
            } else {
                array_push($serversStatistics[$serverId], $serverStatisticDate);
            }

        }

        // Refresh the servers table
        $saveToDb = [];
        foreach ($serversList as $serverData) {

            $currentServerName = $serverData['serverName'];
            $serverId = $serverData['serverId'];

            // Collect all records related to the server
            foreach ($serversRawStatistics[$currentServerName] as $statistic) {

                $date = $statistic->data_label;
                $value = $statistic->data_value;

                // If the database already contains a value for the same timestamp and server, we skip it
                if (!empty($serversStatistics[$serverId]) && in_array($date, $serversStatistics[$serverId])) {
                    continue;
                }

                $saveToDb[] = [
                    'servers_statistics_server_id' => $serverId,
                    'servers_statistics_value' => $value,
                    'servers_statistics_date' => $date,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

        }

        // Update only if we have new record
        if (!empty($saveToDb)) {
            ServersStatistics::insert($saveToDb);
        }

        return true;
    }

    /**
     * Get all statistic by server id
     *
     * @param int $serverId
     * @return array
     */
    public static function getStatisticSummaryById(int $serverId)
    {

        // Get all servers statistic
        return ServersStatistics::select(
            array(
                DB::raw("AVG(`servers_statistics_value`) as average"),
                DB::raw("MIN(`servers_statistics_value`) as min"),
                DB::raw("MAX(`servers_statistics_value`) as max")
            )
        )
            ->where('servers_statistics_server_id', '=', $serverId)
            ->get()
            ->toArray();

    }

    /**
     * Get the selected server statistic
     *
     * @param int $serverId
     * @return mixed
     */
    public static function getStatisticById(int $serverId)
    {

        // Get one server statistic
        return ServersStatistics::select('servers_statistics_value')
            ->where('servers_statistics_server_id', $serverId)
            ->get()
            ->toArray();

    }
}
