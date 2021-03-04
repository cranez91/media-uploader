<?php

namespace App\Modules;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Log;

class Requester
{
    /** @var GuzzleHttp\Client $client */
    private $client;

    function __construct() {
      $this->client = new Client();
    }

    /**
     *
     * Requests a GET URL using a GuzzleHttp client
     *
     * @param string $url The API URL to be requested
     * @return object|null $responseData
     *
     */
    public function get(string $url){
      $responseData = null;
      try {
          $response = $this->client->request("GET", $url);
          $responseData = json_decode($response->getBody()->getContents());
      } catch (RequestException $e) {
          throw $e;
      }
      return $responseData;
    }

    /**
     *
     * Requests a POST URL using a GuzzleHttp client
     *
     * @param string $url The API URL to be requested
     * @return object|null $responseData
     *
     */
    public function post(string $url, array $data){
        $responseData = null;
        try {
            $response = $this->client->request("POST", $url, [
                'form_params' => [
                    $data["field"] => $data["value"]
                ]
            ]);
            $responseData = json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            throw $e;
        }
        return $responseData;
      }

    /**
     *
     * Async Request to GET URL using a GuzzleHttp client
     *
     * @param string $url The API URL to be requested
     * @return object|null $responseData
     *
     */
    public function getAsync(string $url){
      $responseData = null;
      try {
          $promise = $this->client->requestAsync('GET', $url)->then(
              function ($response) {
                  return $response->getBody();
              }, function ($exception) {
                  return $exception->getMessage();
              }
          );
          $response = $promise->wait();
      } catch (RequestException $e) {
          throw $e;
      }
      return $responseData;
    }
}