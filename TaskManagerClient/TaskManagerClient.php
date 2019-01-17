<?php

ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);

require_once 'TaskManagerRequest.php';
require_once 'taskIterator.php';

class TaskManagerClient
{
    /**
     * @var taskIterator
     */
    private $tasks;

    private $taskManagerUrl;
    private $postTaskUrl;
    private $getStatusUrl;

    public function __construct($baseUrl, $postTaskUrl, $getStatusUrl)
    {
        $this->taskManagerUrl = TaskManagerRequest::getInstance($baseUrl);
        $this->postTaskUrl = $postTaskUrl;
        $this->getStatusUrl = $getStatusUrl;
    }

    public function sendTasks(array $tasks=null)
    {
        $this->setTasks($tasks);
        $errorRequestsNumber = 0;
        do {
            if (!$this->tasks->validCurrentTask()) {
                break;
            }
            sleep(TIMEOUT);
            $task = $this->tasks->currentTask();
            $uuid = $this->tasks->getUuidTask();
            $result = $this->taskManagerUrl->request('POST', $this->postTaskUrl, $uuid, $task);
//
//            print_r($result);
//            echo "<br>";
//
            $result = json_decode($result);
            if ($result->status == "500" && $errorRequestsNumber < MAX_ERROR_REQUESTS-1) {
                $errorRequestsNumber++;
                continue;
            }
            if ($result->status == "500" && $errorRequestsNumber >= MAX_ERROR_REQUESTS-1) {
                $this->setStatus(null, 'error');
                $errorRequestsNumber = 0;
                continue;
            }
//            $statusTask = $this->requestStatusTask($result->id);
//            switch ($statusTask) {
//                case 1:
//                    $this->setStatus($result->id, 'success');
//                    $errorRequestsNumber = 0;
//                    break;
//                case 0:
//                    $this->setStatus($result->id, 'pending');
//                    $errorRequestsNumber = 0;
//                    break;
//                case -1:
//                    $errorRequestsNumber++;
//            }
            $server_id = $result->id;
            $pendingRequestsNumber = 0;
            do {
                sleep(TIMEOUT);
                $result = $this->requestStatusTask($server_id);
//
//                echo "--------------";
//                print_r($result);
//                echo "<br>";
                $result = json_decode($result);
                switch ($result->status) {
                    case 'success':
                        $this->setStatus($server_id, 'success');
                        $errorRequestsNumber = 0;
                        break(2);
                    case 'pending':
                        $pendingRequestsNumber++;
                        break;
                    case 'error':
                        $errorRequestsNumber++;
                        break(2);
                }
                if ($pendingRequestsNumber == MAX_ERROR_REQUESTS) {
                    $this->setStatus($server_id, 'pending');
                    $errorRequestsNumber = 0;
                    break;
                }
            } while (true);
        } while (true);
    }

    public function requestStatusTask($server_id)
    {
        return $this->taskManagerUrl->request('GET', $this->getStatusUrl, $server_id);
//        $pendingRequestsNumber = 0;
//        do {
//            sleep(TIMEOUT);
//            $result = $this->taskManagerUrl->request('GET', $this->getStatusUrl, $server_id);
//
//                echo "--------------";
//                print_r($result);
//                echo "<br>";
//            $result = json_decode($result);
//            switch ($result->status) {
//                case 'success':
//                    return 1;
//                case 'pending':
//                    $pendingRequestsNumber++;
//                    break;
//                case 'error':
//                    return -1;
//            }
//            if ($pendingRequestsNumber == MAX_ERROR_REQUESTS) {
//                return 0;
//            }
//        } while (true);
    }

    public function getStatus()
    {
        if ($this->tasks == null) {
            return null;
        }
        return $this->tasks->getResult();
    }

    private function setStatus(?int $server_id, string $status)
    {
        $result['server_id'] = $server_id;
        $result['status'] = $status;
        $this->tasks->setResult($result);
        $this->tasks->nextTask();
    }

    private function setTasks(array $tasks=null)
    {
        $this->tasks = new taskIterator($tasks);
    }
}