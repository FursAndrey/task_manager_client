<?php

class Db
{
    private static $instance = null;

    private function __construct(){}
    private function __clone(){}

    private static function getInstance()
    {
        if (null === self::$instance) {
            $dns = "mysql:dbname=" . getenv('DB_NAME') . ";host=" .getenv('DB_HOST_PDO');
            self::$instance = new PDO($dns, getenv('DB_USER'), getenv('DB_PASS'));
        }
        return self::$instance;
    }

    public static function selectAll($tableName)
    {
        $pdo = Db::getInstance();
        $queryStr = "SELECT * FROM `$tableName`";
        $query = $pdo->query($queryStr);

        $i = 0;
        while ($row = $query->fetch()) {
            $tasksFromDB[$i]['id'] = $row['id'];
            $tasksFromDB[$i]['head'] = $row['head'];
            $tasksFromDB[$i]['text'] = $row['text'];
            $i++;
        }

        return $tasksFromDB;
    }

    public static function selectInOrderLimit($tableName, $sort, $firstRow, $limitRow)
    {
        $pdo = Db::getInstance();
        $queryStr = "SELECT * FROM `$tableName` ORDER BY $sort LIMIT $firstRow,$limitRow";
        $query = $pdo->query($queryStr);

        $i = 0;
        while ($row = $query->fetch()) {
            $tasksFromDB[$i]['id'] = $row['id'];
            $tasksFromDB[$i]['head'] = $row['head'];
            $tasksFromDB[$i]['text'] = $row['text'];
            $i++;
        }

        return $tasksFromDB;
    }
}
