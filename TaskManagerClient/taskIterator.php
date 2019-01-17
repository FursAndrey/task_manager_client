<?php

ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);

require_once 'UUIDGenerate.php';

class taskIterator
{
    private $tasks = array();
    private $total = 0;
    private $pointer = 0;

    public function __construct(array $tasks=null)
    {
        if (!is_null($tasks)) {
            $this->tasks = $tasks;
            $this->total = count($tasks);
        }
    }

    private function getTask(int $num)
    {
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if ( isset($this->tasks[$num])) {
            return $this->tasks[$num];
        }
    }

    public function currentTask()
    {
        return $this->getTask($this->pointer);
    }

    public function nextTask()
    {
        if ($this->validCurrentTask()) {
            $this->pointer++;
        }
    }

    public function validCurrentTask()
    {
        return (!is_null($this->currentTask()));
    }

    public function setResult(array $result=null)
    {
        $this->tasks[$this->pointer]['result'] = $result;
    }

    public function getResult()
    {
        return $this->tasks;
    }

    public function getUuidTask()
    {
        if (!isset($this->tasks[$this->pointer]['uuid'])){
            $this->tasks[$this->pointer]['uuid'] = UUIDGenerate::generateUuid();
        }
        return $this->tasks[$this->pointer]['uuid'];
    }

//    public function getKeyTask()
//    {
//        return $this->pointer;
//    }

//    public function rewind()
//    {
//        $this->pointer = 0;
//    }

//    public function getTotal()
//    {
//        return $this->total;
//    }
}