<?php

class MessagesDatabaseAccessor {

    private $pdo;

    function __construct(){
        $config = parse_ini_file('./config.ini'); 
        $dsn = "mysql:host=127.0.0.1;dbname=" . $config['dbname'] . ";";
        $username = $config['username'];
        $password = $config['password'];
        
        try {
            $tempPDO = new PDO($dsn, $username, $password);
        }
        catch (PDOException $e) {
            $tempPDO = null;
            echo 'Connection failed: ' . $e->getMessage();
            //TODO: better error handling
        }
        
        $this->pdo = $tempPDO;
    }
    
    function sanitize($str){
        //$str = str_replace('{ampersand}', "&", $str);
        $str = str_replace('&', "&amp;", $str);
        //$str = str_replace('{equals sign}', "=", $str); //this is weird in widgetContent.js for posting purposes
        $str = str_replace("<", "&lt;", $str);
        $str = str_replace(">", "&gt;", $str);
        $str = str_replace('"', "&quot;", $str);
        $str = str_replace("'", "&apos;", $str);

        return $str;
    }
    
    function getMessageRowCount(){
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS num
            FROM message;
            ");
        
        $stmt->execute(array());
        
        if ($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            $num = $result[0]['num'];
        }

        return $num;
    }
    
    function insertMessage($msg){
        $msg = $this->sanitize($msg);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO message (message)
            VALUES (?);
            ");
        
        return $stmt->execute(array($msg));
    }
    
    function selectRandomMessage(){
        $stmt = $this->pdo->prepare("
            SELECT m.id, m.message, m.date_created
            FROM message AS m
            JOIN (
                SELECT FLOOR(RAND()*COUNT(*))+1 AS id
                FROM message
                ) AS m2 ON m.id = m2.id;
            ");
        
        $stmt->execute(array());
        
        if ($stmt->rowCount() > 0){
            $result = $stmt->fetchAll();
            $messageData = $result[0];
        }
        
        for($x=0; $x<=2; $x++){
            unset($messageData[$x]);
        }
        
        $stmt2 = $this->pdo->prepare("
            UPDATE message
            SET times_read = times_read + 1
            WHERE id = ?;
            ");
        
        $stmt2->execute(array($messageData['id']));
        
        unset($messageData['id']);
        
        //$messageData is an array of the form: ['message'=>'blah blah', 'date_created'=>'2018-04-18 13:02:53']
        return $messageData;
    }

}


?>