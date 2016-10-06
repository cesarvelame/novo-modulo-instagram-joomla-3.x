<?php    
$document = JFactory::getDocument(); 
$document->addStyleSheet('modules/mod_instagram/css/mod_instagram.css');
$document->addScript('modules/mod_instagram/js/mod_instagram.js');
$module = JModuleHelper::getModule('mod_instagram');
$moduleParams = new JRegistry();
$moduleParams->loadString($module->params);
$token = $moduleParams->get('token');
$api = "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$token."&count=20";

function get_curl($url) {
    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        $output = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        return $output;
    } else{
        return file_get_contents($url);
    }
}

$images = array();

$cache = 'modules/mod_instagram/cache/cache.json';

if(file_exists($cache) && filemtime($cache) > time() - 60*60){
    // If a cache file exists, and it is newer than 1 hour, use it
    $images = json_decode(file_get_contents($cache),true); //Decode as an json array
} else {
    // Make an API request and create the cache file
    // For example, gets the 32 most popular images on Instagram
    $response = get_curl($api); //change request path to pull different photos

    $images = array();

    if($response){
        // Decode the response and build an array
        foreach(json_decode($response)->data as $item){

            $title = $item->caption->text;
            $src = $item->images->thumbnail->url; //Caches standard res img path to variable $src
			$link = $item->link;
            $images[] = array(
                "title" => htmlspecialchars($title),
				"link" => htmlspecialchars($link),
                "src" => htmlspecialchars($src)
            );        
            file_put_contents($cache,json_encode($images)); //Save as json
        }
    }    
 }

//Debug out
	echo "<div id='instagram'>";
		 foreach ($images as $key => $value) {
			echo '<a href="'. $value['link'] .'" title="'. $value['title'] .'" target="_blank"><img src="'. $value['src'] .'" class="thumb" alt="'. $value['title'] .'"/></a>';
		}
	echo "</div>"
?>