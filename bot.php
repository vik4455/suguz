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
		switch($event['message']['type']) {
            case 'text': {
                    switch(strtolower($event['message']['text'])) { 
                        case 'm':
                            $respMessage='What sup man.Go away!';
                            break; 
                        case 'f':
                            $respMessage='Love you lady.';
                            break; 
                        default:
                            $respMessage='What is your sex? M or F'; 
                        break;
                    }
                }
                $textMessageBuilder=new TextMessageBuilder($respMessage);
                break;
            case 'image':
                $originalContentUrl = 'https://img.purch.com/w/660/aHR0cDovL3d3dy5zcGFjZS5jb20vaW1hZ2VzL2kvMDAwLzAwNS82NDQvb3JpZ2luYWwvbW9vbi13YXRjaGluZy1uaWdodC0xMDA5MTYtMDIuanBn';
                $previewImageUrl = 'https://img.purch.com/w/660/aHR0cDovL3d3dy5zcGFjZS5jb20vaW1hZ2VzL2kvMDAwLzAwNS82NDQvb3JpZ2luYWwvbW9vbi13YXRjaGluZy1uaWdodC0xMDA5MTYtMDIuanBn';
                $textMessageBuilder=new ImageMessageBuilder($originalContentUrl, $previewImageUrl);
                //$messageID = $event['message']['id']; 
                //$respMessage='Hello, your image ID is '.$messageID;
                break;
            case 'sticker':
                $packageId = 1; 
                $stickerId = 3;
                $textMessageBuilder=new StickerMessageBuilder($packageId, $stickerId);
                break;
            case 'location':
                $address = $event['message']['address'];
                $respMessage='Hello, your address is '.$address;
                break;
//            case 'video':
//                $messageID = $event['message']['id'];
//                $fileID = $event['message']['id'];
//                $response = $bot->getMessageContent($fileID); 
//                $fileName = 'linebot.mp4'; 
//                $file=fopen($fileName, 'w');
//                fwrite($file, $response->getRawBody());
//                $respMessage='Hello, your video ID is '.$messageID;
//                break;
//            case 'audio':
//                $messageID = $event['message']['id'];
//                $fileID = $event['message']['id'];
//                $response = $bot->getMessageContent($fileID); 
//                $fileName = 'linebot.m4a'; $file=fopen($fileName, 'w');
//                fwrite($file, $response->getRawBody());
//                $respMessage='Hello, your audio ID is '.$messageID;
//                break;
            default:
                $respMessage='What is you sent ?'; 
                break;
        }
        
        $httpClient = new CurlHTTPClient($channel_token);
        $bot=new LINEBot($httpClient, array('channelSecret'=> $channel_secret));
        
        $response=$bot->replyMessage($replyToken, $textMessageBuilder);
    }
}

echo "OK";