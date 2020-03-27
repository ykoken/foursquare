<?php


namespace App\Services;

use App\Exceptions\FourSquareException;
use GuzzleHttp\Client;

class FourSquareApiServices
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCategories($url, $parameters = [])
    {
        return $this->getAllCategories(
            json_decode($this->getRemoteApiData($url, $parameters)->getBody(), true)
        );
    }

    public function getLocations($url, $parameter)
    {
        $getRemotedata = $this->getRemoteApiData($url, $parameter);
        return $this->getRemoteApiLocationData(json_decode($getRemotedata->getBody(), true));
    }

    private function getRemoteApiData($url, $parameters)
    {
        $parameters = array_merge($parameters, $this->getAuth());
        $requestUrl = env('FOURSQUARE_URL') . $url . '?' . http_build_query($parameters);
        $requestData = $this->client->request('GET', $requestUrl);

	    if ($requestData->getStatusCode() !== 200) {
		    throw new FourSquareException("FOURSQUARE API error: Status code " . $requestData->getStatusCode());
	    }

	    return $requestData;
    }

    private function getAuth()
    {
    	//Foursquare Api Client Info
        return [
            'client_id' => env('FOURSQUARE_CLIENT_ID'),
            'client_secret' => env('FOURSQUARE_CLIENT_SECRETKEY'),
            'v' => date('Ymd'),
        ];
    }

    public function getAllCategories($data)
    {
    	$categories=[];
        foreach ($data['response']['categories'] as $item) {
            $categories[] = $item;
        }
        return $categories;
    }

    public function getRemoteApiLocationData($data)
    {
    	$locations=[];
        foreach ($data['response']['groups'][0]['items'] as $item) {
            $locations[] = $item;
        }
        return $locations;
    }

}
