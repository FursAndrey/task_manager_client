<?php

ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);

require_once 'Db.php';
//require_once 'TaskManagerClient.php';
require_once 'TaskManagerClient/TaskManagerClient.php';

//инициализировать константы
//TIMEOUT
//MAX_ERROR_REQUESTS
define('TIMEOUT', 0);
define('MAX_ERROR_REQUESTS', 5);

$baseUrl = 'http://172.11.0.4/';
$postTaskUrl = 'task.php';
$getStatusUrl = 'index.php';
//$tasksFromDB = Db::selectAll('tasks');
//$tasksFromDB = Db::selectInOrderLimit('tasks', 'id', 3, 3);

//иммитация БД
$tasksFromDB = [
    [
        'id' => 1,
        'head' => 'h1',
        'text' => 'text1',
    ],
    [
        'id' => 2,
        'head' => 'h2',
        'text' => 'text2',
    ],
    [
        'id' => 3,
        'head' => 'h3',
        'text' => 'text3',
    ],
];

$startTime = microtime(true);

$task = new TaskManagerClient($baseUrl, $postTaskUrl, $getStatusUrl);

//$task->setTasks($tasksFromDB);
//
//$task->sendTasks();
//$result = $task->getStatus();
//echo "<pre>";
//print_r($result);

///////////////////////////////////////////////////////
$task->sendTasks($tasksFromDB);
$result = $task->getStatus();

echo microtime(true) - $startTime;
echo "<br/>";
//echo "<br>Finish";
//
echo "<pre>";
print_r($result);
//print_r($tasksFromDB);