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
            
            //Split message then keep it in database. 
            $appointments=explode(',', $event['message']['text']);
            
            if(count($appointments) == 2) {
                $host = 'ec2-54-235-65-224.compute-1.amazonaws.com';
                $dbname = 'd57b0s2qa541bq'; 
                $user = 'gfqphhprpuzrre';
                $pass = 'e1c9b3a5cf6a2d33f100944a04ac4b99b53ce0036341b51a0a9988a6e2d527a2'; 
                $connection=new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
                $params = array('time'=> $appointments[0], 'content'=> $appointments[1],);
                
                $statement=$connection->prepare("INSERT INTO appointments (time, content)VALUES(:time,:content)");
                $result = $statement->execute($params);
                $respMessage='Your appointment has saved.';
            }else{
            $respMessage='You can send appointment like this "12.00,House keeping."'; 
            }
        }
        $httpClient = new CurlHTTPClient($channel_token);
        $bot=new LINEBot($httpClient, array('channelSecret'=> $channel_secret)); 
        $textMessageBuilder=new TextMessageBuilder($respMessage);
        $response=$bot->replyMessage($replyToken, $textMessageBuilder);
    }
}

echo "OK";