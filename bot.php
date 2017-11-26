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
                $respMessage = "XX";    
        }
            
    }
        
        $httpClient = new CurlHTTPClient($channel_token);
        $bot=new LINEBot($httpClient, array('channelSecret'=> $channel_secret));
        
        $textMessageBuilder=new TextMessageBuilder($respMessage);
        $response=$bot->replyMessage($replyToken, $textMessageBuilder);
            
    }
}

echo "OK";