<?php

ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
use GuzzleHttp\Client;

class TaskManagerRequest
{
    private static $instance = null;
    private $client;
    private function __construct($baseUri)
    {
        $this -> client = new Client([
            'base_uri' => $baseUri,
        ]);
    }
    private function __clone(){}

    public static function getInstance($baseUri)
    {
        if (null === self::$instance) {

            self::$instance = new TaskManagerRequest($baseUri);
        }
        return self::$instance;
    }


    public function request(string $method, string $urlGetStatus, string $id, $tasks=[])
    {
//        switch ($method) {
//            case 'POST':
//                $requestArgument = array(
//                    'form_params' => [
//                        'uuid' => $id,
//                        'task' => $tasks,
//                    ],
//                    'http_errors' => false,
//                );
//                break;
//            case 'GET':
//                $requestArgument = array(
//                    'query' => 'id='.$id,
//                );
//                break;
//            default:
//                return false;
//        }
//        $response = $this->client->request ($method, $urlGetStatus, $requestArgument);
//        if ($response->getStatusCode() == 500) {
//            $stringBody = json_encode(
//                [
//                    "status" => '500',
//                ]
//            );
//        } else {
//
//            $body = json_decode($response->getBody(),true);
//            if (!isset($body['status'])) {
//                $body['status'] = '200';
//            }
//            $stringBody = json_encode($body);
//        }
//
//        return $stringBody;
        if($method == 'POST'){
            $int = random_int(0, 1);
//            $int = 0;
            if($int==0) {
                $body = [
                    'status' => '500',
                ];
            } else {
                $body = [
                    'status' => '200',
                    "id" => $tasks['id']*10,
                    "taskHead" => $tasks['head'],
                    'uuid' => $id,
                ];
            }
        } elseif ($method == 'GET'){
            $int = random_int(0, 2);
            $response = [
                'success',
                'pending',
                'error'
            ];
            $body = (
            [
                "status" => $response[$int],
                "id" => $id,
            ]
            );
        }
        $stringBody = json_encode($body);
        return $stringBody;
    }
}