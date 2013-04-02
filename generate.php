<?php

	function getStuff($url){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
		return $server_output;
	}

	function print_array($in_array){
		echo "<pre>";
		print_r($in_array);
		echo "</pre>";
	}

	$content = getStuff($_POST['pl_url']);

	$DOM = new DOMDocument;
	$DOM->loadHTML( $content);

	$items = $DOM->getElementsByTagName('span');

	$j=0;
	for ($i = 0; $i < $items->length; $i++){
	  if($items->item($i)->getAttribute('class') == 'track'){
	  		$tracks[$j]['track_name'] =$items->item($i)->nodeValue;
	  		++$j;
	  }
	}
	
	$i =0;
	foreach($tracks as $track){
		//$content = file_get_contents('https://gdata.youtube.com/feeds/api/videos?alt=json&q='.urlencode($track));
		$content = getStuff('https://gdata.youtube.com/feeds/api/videos?alt=json&q='.urlencode($track['track_name']));
		$content = json_decode($content, true);
		$tracks[$i]['title'] = $content['feed']['entry'][0]['title']['$t'];
		$tracks[$i]['link'] = $content['feed']['entry'][0]['link'][0]['href'];
		$tracks[$i]['link'] = substr($tracks[$i]['link'], 32, 11);
		++$i;
	}

	for($i = 0; $i < count($tracks); ++$i){
		echo "<div><iframe id='ytplayer' type='text/html' width='640' height='390' src='http://www.youtube.com/embed/".$tracks[$i]['link']."' frameborder='0'></iframe></div>";
	}
?>
