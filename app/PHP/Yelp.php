<?php
/**
 * Created by PhpStorm.
 * User: ageorge
 * Date: 7/30/2020
 * Time: 12:37 AM
 */

namespace App\PHP;

// General class to connect with Yelp API
class Yelp
{
    private $client_id;
    private $initURL;
    private $key;
    protected $name;
    protected $header;

    public function __construct(){
        $this->client_id = "CORKhzT7twiMt-9I6QiC-w";
        $this->key = "PAUY16r4tfpcFIgiVgaGcyOCp4RoXrSbiNfD9qcc2_Tlu-_ptU8g0N_HFd_Lv4R_bj0o9od4lH2YhottXLNJ7Ywh3ckz1GswdhNvUCWrdwbN9jCkafmpdz7xV04iX3Yx";
        $this->name = "APIPassport";
        $this->initURL = "https://api.yelp.com/v3";
        $this->setHeader();
    }

    /**
     * Set the default headers for requests
     *
     * @param array $header
     * @return void
     */
    public function setHeader(array $header = null){
        if($header){
            $this->header = $header;
        } else {
            $this->header = [
                "authorization: Bearer " . $this->key,
                "cache-control: no-cache",
            ];
        }
    }

    /**
     * Makes a request to the Yelp API and returns the response
     *
     * @param $path         // The path of the API after the domain.
     * @param $parameters   // Array of search parameters.
     * @return
     */
    public function getRequest($path, $parameters = array()){
        // Send Yelp API Call
        try {
            $curl = curl_init();
            if ($curl === false){
                return $this->buildResponse("Failed to initialize", false);
            }

            $url = $this->initURL . $path . "?" . http_build_query($parameters);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,     // Capture response.
                CURLOPT_ENCODING => "",             // Accept gzip/deflate/whatever.
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => $this->header,
            ));

            $response = curl_exec($curl);

            if ($response === false){
                $exception = new \Exception(curl_error($curl), curl_errno($curl));
                $message = $exception->getMessage();
                if (call_user_func(array(Utils::class, 'isJSON'), $exception->getMessage())){
                    $message = call_user_func(array(Utils::class, 'jsonToArray'), $exception->getMessage());
                }
                return $this->buildResponse($message, false);
            }

            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_status != 200){
                $exception = new \Exception($response, $http_status);
                $message = $exception->getMessage();
                if (call_user_func(array(Utils::class, 'isJSON'), $exception->getMessage())){
                    $message = call_user_func(array(Utils::class, 'jsonToArray'), $exception->getMessage());
                }
                return $this->buildResponse($message, false);
            }

            curl_close($curl);
        } catch(\Exception $e) {
            return $this->buildResponse("Curl failed. " . $e->getMessage(), false);
        }

        if(call_user_func(array(Utils::class, 'isJSON'), $response)){
            $response = call_user_func(array(Utils::class, 'jsonToArray'), $response);
        }
        return $this->buildResponse($response);
    }

    private function buildResponse($response, $status = true){
        $result['status'] = $status;
        $result['response'] = $response;
        return $result;
    }
}
