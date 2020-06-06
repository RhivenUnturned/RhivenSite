<?php

namespace App\Console\Commands;

use Google_Client;
use Google_Service_YouTube;
use Illuminate\Console\Command;
use Storage;

class GetVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Misc:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the Youtube videos about a subject and save them in a file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * Sample PHP code for youtube.search.list
         * See instructions for running these code samples locally:
         * https://developers.google.com/explorer-help/guides/code_samples#php
         */

        $client = new Google_Client();
        $client->setApplicationName('API code samples');
        $client->setDeveloperKey("AIzaSyCsTwVS1O5I5H_KmbmxJ5QSYVY4yHX68Bs");

        // Define service object for making API requests.
        $service = new Google_Service_YouTube($client);

        $queryParams = [
            'maxResults' => 15,
            'q' => 'Rhiven Unturned|Serveur Rhiven RP -omega -layoka -gadu -mg',
            'regionCode' => 'FR',
            'relevanceLanguage' => 'fr',
            'type' => 'video'
        ];

        $response = $service->search->listSearch('snippet', $queryParams);

        $response = json_encode($response['items']);
        $response = json_decode($response, true);

        $videosIdsToExclude = array('s8Ph9nmphyQ');

        foreach ($response as $key => $item) {
            if (in_array($item['id']['videoId'], $videosIdsToExclude, true)) {
                unset($response[$key]);
            }
        }

        $response = json_encode($response);

        Storage::disk('public')->put('videos.json', $response);
    }
}
