<?php

namespace App\Http\Controllers;

use App\Servers;
use App\ServersStatistics;
use Illuminate\Http\Request;

class ServersController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the servers list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Get all servers
        $servers = Servers::all();

        // Collect the server statistic
        $serversStatistic = [];

        foreach ($servers as $server) {
            $serverId = $server->id;

            $serversStatisticRaw = ServersStatistics::getStatisticSummaryById($serverId);
            $serversStatistic[$serverId] = $serversStatisticRaw[0];

        }

        return view('servers')->with(['servers' => $servers, 'serversStatistic' => $serversStatistic]);

    }

    /**
     * Show the selected server statistic
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statistic(Request $request)
    {

        $serverId = $request->id;

        // Get server name
        $serverDetails = Servers::find($serverId)->toArray();

        // Get the server details
        $serversStatisticRaw = ServersStatistic::getStatisticSummaryById($serverId);
        $serversStatistic[$serverId] = $serversStatisticRaw[0];

        return view('statistic', with(['serverId' => $serverId, 'serversStatistic' => $serversStatistic, 'serverName' => $serverDetails['server_name']]));

    }

    /**
     * Get statistic JSON by server ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chart(Request $request)
    {
        $result = ServersStatistic::getStatisticById($request->id);

        $counter = 0;
        foreach ($result as $data) {

            $data = $data['servers_statistics_value'];

            $response[] = ['minute' => $counter, 'data' => $data];

            $counter++;

        }

        return response()->json($response);
    }

}
