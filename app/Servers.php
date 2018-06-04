<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Servers extends Model
{
    /**
     * Insert the new servers to the database
     *
     * @param array $serversRawList
     * @return bool
     */
    public static function insertToTheDb(array $serversRawList)
    {

        // Check the existing servers
        $serversListInTheDb = Servers::select('server_name')->get()->toArray();
        $servers = [];
        $serversList = [];

        // Make an array
        foreach ($serversListInTheDb as $result) {
            $servers[] = $result['server_name'];
        }

        foreach ($serversRawList as $result) {
            $serversList[] = $result;
        }

        $serversInsertList = array_diff($serversList, $servers);

        // Nothing to update
        if(empty($serversInsertList)) {
            return true;
        }

        // Refresh the servers table
        $saveToDb = [];
        foreach ($serversInsertList as $serverName) {

            $saveToDb[] = [
                'server_name' => $serverName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

        }

        Servers::insert($saveToDb);

        return true;
    }
}
