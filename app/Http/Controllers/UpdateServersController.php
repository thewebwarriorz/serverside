<?php

namespace App\Http\Controllers;

use App\Servers;
use App\ServersStatistics;
use GuzzleHttp\Client;

class UpdateServersController extends Controller
{
    // Fetch data from this URL-s
    private $serversListUrl = null;
    private $serversStatisticsUrl = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->serversListUrl = env('SERVER_LIST_URL', 'default');
        $this->serversStatisticsUrl = env('SERVER_STATISTICS_URL', 'default');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('update');
    }

    /**
     * Update the servers and servers statuses incrementally.
     *
     * @return \Illuminate\Http\Response
     */
    public function doUpdate()
    {

        $client = new Client();
        $response = $client->get($this->serversListUrl);
        $responseStatusCode = $response->getStatusCode();
        $responseObject = \GuzzleHttp\json_decode($response->getBody());

        // Check the request was successful
        if ($responseStatusCode !== 200) {

            // Something wrong, set an error message and go back to the refresh page
            session()->flash('message_important', 'The refresh was not successful. The remote server has given an error code: ' . $responseStatusCode);
            return view('update');

        }

        // Collect the error if have any
        $serverErrors = [];

        // Collect the data
        $updateServerData = [];
        $serverNames = [];

        // Start parsing the servers and get the statistics
        foreach ($responseObject as $serverData) {

            $serverName = $serverData->s_system;
            $serverNames[] = $serverName;

            // Get the server statistics
            $serverResponse = self::getServerStatistic($serverName);
            $serverResponseCode = $serverResponse['status'];

            // If an error happened when we fetch the server status, we skip for the update cycle, and set an error message
            if ($serverResponse['status'] !== 200) {
                $serverErrors[] = 'The following server: ' . $serverName . ' refresh was not successful. The remote server has given an error code: ' . $serverResponseCode;
                continue;
            }

            $updateServerData[$serverName] = $serverResponse["data"];

        }

        // Refresh the servers table
        Servers::insertToTheDb($serverNames);

        // Refresh the servers the servers statistics table
        ServersStatistics::insertToTheDb($updateServerData);

        // Implode the errors, if have any
        if (!empty($serverErrors)) {
            session()->flash('message_important', implode("\n\r | ", $serverErrors));
            return view('update');
        } else {
            session()->flash('message', 'Update was successful!');
            return view('home');
        }

    }

    /**
     * Get the given server statistic by server name
     *
     * @param string $serverName
     * @return array
     */
    private function getServerStatistic(string $serverName)
    {

        $client = new Client();
        $response = $client->get($this->serversStatisticsUrl . "$serverName/");
        $responseStatusCode = $response->getStatusCode();
        $responseObject = \GuzzleHttp\json_decode($response->getBody());

        // Check the request was successful
        if ($responseStatusCode !== 200) {

            // Something wrong, set an error message and go back to the refresh page

            return ["status" => $responseStatusCode, "data" => null];

        }

        return ["status" => $responseStatusCode, "data" => $responseObject];

    }

}
