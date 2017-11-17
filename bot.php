<?php
require_once('./vendor/autoload.php');
// Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

$channel_token = '9Isri8d/LYfcip6hCv4kmAUHR6ST8tTwUYC69hCFEb0wG7TNHEhqqVSrW+KV9rhM5c6xseoifi5zV53qv9L2IBEY8w9Fsn4yv6pLGLsvEIdfGOJrmG2WYB6B7c2X8/McMCSul3HXWnWTqVbK02fWzQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'ee36357fc71a88bf26ba4bb51ed4870d';

//Get message from Line API
$content = file_get_contents('php://input');
$events=json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
        $replyToken = $event['replyToken']; 
		switch($event['message']['type']) {
            case 'image':
                $messageID = $event['message']['id']; 
                $respMessage='Hello, your image ID is '.$messageID;
                break; 
            default:
                $respMessage='Please send image only'; 
                break;
        }
        
        $httpClient = new CurlHTTPClient($channel_token);
        $bot=new LINEBot($httpClient, array('channelSecret'=> $channel_secret));
        $textMessageBuilder=newTextMessageBuilder($respMessage);
        $response=$bot->replyMessage($replyToken, $textMessageBuilder);
    }
}

echo "OK";