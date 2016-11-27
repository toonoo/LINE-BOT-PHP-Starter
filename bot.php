<?php
$access_token = 'StMQQmUud2pB5f5h2RqjfdOnwO+zrrwsVGJWAkQ6K/+RBVJAHMps/OcPFQ65LcGKMhRRfbMRIBWKp9pELtIxjEg8lK5ol2SAobl8Drfg30y4aSXQtcvo5woZwet8WEXLVRDjMD+us6viLi+YEPr2LAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			//ckeck word in message
			// $ch1 = curl_init();
			// curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false); 
			// curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true); 
			// curl_setopt($ch1, CURLOPT_URL, 'https://th.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text); 
			// $result1 = curl_exec($ch1); 
			// curl_close($ch1); 

			// $obj = json_decode($result1, true); 
			// foreach($obj['query']['pages'] as $key => $val)
			// { 
			// 	$text = $val['extract']; 
			// }

			// if($text == "")
			// {
			// 	$text = 'ไม่มีข้้อมูลใน Wiki thai แมะ!!!';
			// }
			// if($text == "dog")
			// {
			    $apiKey = 'AIzaSyA2MmYjmmWn4Wg2JSVtIcSJEngNntvQKU0';
			    //$text = 'Hello world!';
			    $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&source=en&target=th';

			    $handle = curl_init($url);
			    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			    $response = curl_exec($handle);
			    $responseDecoded = json_decode($response, true);
			    $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);      //Here we fetch the HTTP response code
			    curl_close($handle);

			    if($responseCode != 200) 
			    {
			        //$text = 'Fetching translation failed! Server response code:' . $responseCode . '<br>';
			        $text = 'Error description: ' . $responseDecoded['error']['errors'][0]['message'];
			    }
			    else 
			    {
			        $text = $responseDecoded['data']['translations'][0]['translatedText'];
			    }
			// }else{

			// }

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";