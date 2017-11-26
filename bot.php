<?php
require_once('./vendor/autoload.php');
// Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

$channel_token = '9wekUc+mHLQj9GC8NIUlNqxftM0V+06o7scvwRA6qiNAr4tjnPeWGzTi8ht0FcOz5c6xseoifi5zV53qv9L2IBEY8w9Fsn4yv6pLGLsvEIddjdNLVdz61lOrpfFYAnG/l42YQ89HY2H2bxSPm6UGGAdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'a482c124ff8e612e52bdd084d1acaa4c';

//Get message from Line API
$content = file_get_contents('php://input');
$events=json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
        $replyToken = $event['replyToken']; 
		
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
            //Check exist answer user
            try{
                $host = 'ec2-54-235-65-224.compute-1.amazonaws.com';
                $dbname = 'd57b0s2qa541bq'; 
                $user = 'gfqphhprpuzrre';
                $pass = 'e1c9b3a5cf6a2d33f100944a04ac4b99b53ce0036341b51a0a9988a6e2d527a2';
                $connection=new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
                
                $sql=sprintf("SELECT*FROM poll WHERE user_id='%s' ", $event['source']['userId']);
                $result = $connection->query($sql);
                error_log($sql);
                
                if($result==false || $result->rowCount()<=0){
                    switch($event['message']['text']) {
                        case '1':
                            // Insert
                            $params = array('userID'=> $event['source']['userId'], 'answer'=> '1',);
                            $statement=$connection->prepare('INSERT INTO poll (user_id,answer)VALUES(:userID, :answer)');
                            $statement->execute($params);
                            // Query
                            $sql=sprintf("SELECT * FROM poll WHERE answer='1' AND user_id='%s'",$event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount(); 
                            }
                            $respMessage='จํานวนคนตอบว่า เพื่อน ='.$amount;
                            break;
                        case '2':
                            // Insert
                            $params = array('userID'=> $event['source']['userId'], 'answer'=> '2',);
                            $statement=$connection->prepare('INSERT INTO poll (user_id,answer)VALUES(:userID, :answer)');
                            $statement->execute($params);
                            // Query
                            $sql=sprintf("SELECT * FROM poll WHERE answer='2' AND user_id='%s'",$event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount(); 
                            }
                            $respMessage='จํานวนคนตอบว่า แฟน ='.$amount;
                            break;
                        case '3':
                            // Insert
                            $params = array('userID'=> $event['source']['userId'], 'answer'=> '3',);
                            $statement=$connection->prepare('INSERT INTO poll (user_id,answer)VALUES(:userID, :answer)');
                            $statement->execute($params);
                            // Query
                            $sql=sprintf("SELECT * FROM poll WHERE answer='3' AND user_id='%s'",$event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount(); 
                            }
                            $respMessage='จํานวนคนตอบว่า พ่อแม่ ='.$amount;
                            break;
                        case '4':
                            // Insert
                            $params = array('userID'=> $event['source']['userId'], 'answer'=> '4',);
                            $statement=$connection->prepare('INSERT INTO poll (user_id,answer)VALUES(:userID, :answer)');
                            $statement->execute($params);
                            // Query
                            $sql=sprintf("SELECT * FROM poll WHERE answer='4' AND user_id='%s'",$event['source']['userId']);
                            $result = $connection->query($sql);
                            $amount = 1;
                            if($result){
                                $amount = $result->rowCount(); 
                            }
                            $respMessage='จํานวนคนตอบว่า บุคคลอื่นๆ ='.$amount;
                            break;
                        default:
                            $respMessage = "
                            คนที่เราโทรหาบ่อยที่สุดคือ \n\r 
                            กด1 เพื่อน\n\r
                            กด2 แฟน\n\r
                            กด3 พ่อแม่\n\r
                            กด4 บุคคลอื่นๆ\n\r ";
                            break;                      
                    }  
                }else {
                    $respMessage = 'คุณได้ตอบโพลล์นี้แล้ว';
                }
            }
        }
        $httpClient = new CurlHTTPClient($channel_token);
        $bot=new LINEBot($httpClient, array('channelSecret'=> $channel_secret));
        
        $textMessageBuilder=new TextMessageBuilder($respMessage);
        $response=$bot->replyMessage($replyToken, $textMessageBuilder);
    }catch(Exception$e){ 
        error_log($e->getMessage());
    }
}

echo "OK";