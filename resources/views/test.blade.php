<form action="" method ="get">
	<input type="text" name="search">
	<input type="submit" value="Search">
</form>

<?php
	
	if($_GET['search']){
		$api_url = "https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&titles=".ucwords($_GET_GET['search'])."redirects=true";
		$api_url = str_replace('','%20',$api_url);
		
		if($data = json_decode(file_get_contents($api_url))){
			foreach($data->query->pages as $key=>$vai){
			$pageId=$key;
			break;
            }
			
			$content = $data->query->pages->$pageidId->extract;
			
			header('Content-Type:text/html; charset=utf-8');
			echo $content;
			
		}
		else
			echo 'No results found';
    }
?>