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
            if($event['message']['text']=="คำถาม"){
                $respMessage = "วันนี้ท๊อฟฟี่จะแพ้อีกรึไม่ กด1 แพ้\n กด2 ไม่แพ้\n";    
            }else if($event['message']['text']=="1" || $event['message']['text']=="2"){
                try{
                $host = 'ec2-54-235-65-224.compute-1.amazonaws.com';
                $dbname = 'd57b0s2qa541bq'; 
                $user = 'gfqphhprpuzrre';
                $pass = 'e1c9b3a5cf6a2d33f100944a04ac4b99b53ce0036341b51a0a9988a6e2d527a2';
                $connection=new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
                
                $sql=sprintf("SELECT * FROM poll WHERE user_id='%s' ", $event['source']['userId']);
                $result = $connection->query($sql);
                error_log($sql);
                
                if($result==false || $result->rowCount()<=0){
                    switch(['message']['text']) {
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
                            $respMessage='จํานวนคนตอบว่า แพ้ ='.$amount.' olo';
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
                            $respMessage='จํานวนคนตอบว่า ไม่แพ้ ='.$amount.' อิอิ';
                            break;
                    }
                }else{
                    $respMessage = 'คุณได้ตอบโพลล์นี้แล้ว ไอ้สัส';
                }
                }catch(Exception $e){ 
                error_log($e->getMessage());
                } 
            }
            
        }
        
        $httpClient = new CurlHTTPClient($channel_token);
        $bot=new LINEBot($httpClient, array('channelSecret'=> $channel_secret));
        
        $textMessageBuilder=new TextMessageBuilder($respMessage);
        $response=$bot->replyMessage($replyToken, $textMessageBuilder);
            
    }
}

echo "OK";