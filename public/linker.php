<?php
	$link = 'http://icheck.com.vn';
	$image= 'https://icheck.com.vn/uploads/images/icheck/logo-icheck.png';
	$description = 'Truy xuất nguồn gốc sản phẩm';
	$name = 'Icheck Scanner';
	if(isset($_GET['id']) && isset($_GET['type'])) {
		$id = $_GET['id'];
        	$type = $_GET['type'];
                $dev = isset($_GET['dev']) ? $_GET['dev'] : 0;
		if($dev == 1) {
	        	$host = 'http://10.5.11.46:8080/api/v1';
		}else {
			$host = 'https://api.icheck.com.vn/api/v1';
		}
		switch($type) {
			case 'product' : {
				$url = $host.'/products/'.$id;
				$info = file_get_contents($url);
				$info = json_decode($info);
				if($info && !empty($info)) {
					$name = $info->name;
					$image = $info->image;
					$description = ($info->informations && !empty($info->informations) && $info->informations[0]->content) ? $info->informations[0]->content : '';
		                       $link = shorten_URL('https://icheck.vn/product?id='.$info->id, $info->barcode);
//					var_dump($link); die('xxx');
				}
				break;
			}
			case 'review': {
				$product_id = isset($_GET['object_id']) ? $_GET['object_id'] : 0;
				
				$url = $host.'/products/'.$product_id;
				$info = file_get_contents($url);
				$info = json_decode($info);
				if($info && !empty($info)) {
					$name = $info->name;
					$image = $info->image;
					$description = ($info->informations && !empty($info->informations) && $info->informations[0]->content) ? $info->informations[0]->content : '';
					$link = shorten_URL('https://icheck.vn/review?object_type=product&object_id='.$product_id, $info->barcode);
				}
				break;
			}
			default : break;
		}
	}
	header('Content-type: application/json');
	echo json_encode(['link' => $link]);

function shorten_URL ($longUrl, $barcode) {
  $key = 'AIzaSyC3y3I6RKQvHLY0mn7Fqxd6nJVgZ1wZ9-U';
  $url = 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=' . $key;
  $data = array(
	"dynamicLinkInfo" => array(
		"domainUriPrefix" => "https://icheck.page.link",
		"link" => $longUrl,
		"androidInfo" =>  array(
			"androidPackageName" => "vn.icheck.android",
			"androidFallbackLink" => "https://play.google.com/store/apps/details?id=vn.icheck.android&hl=vi"
		),
		"iosInfo" => array(
			"iosBundleId" => "vn.icheck.ios",
			"iosFallbackLink" => "https://itunes.apple.com/app/id1001036590?mt=8",
			"iosCustomScheme" => "icheck",
			"iosIpadFallbackLink" => "https://itunes.apple.com/app/id1001036590?mt=8",
			"iosIpadBundleId" => "vn.icheck.ios"
		),
		"desktopInfo" => array(
			"desktopFallbackLink" => "https://icheck.vn/products/".$barcode
		),
		"navigationInfo" => array(
			"enableForcedRedirect" => "1"
		)
    	)
  );

  $headers = array('Content-Type: application/json');

  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_POST, true );
  curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

  $data = curl_exec ( $ch );
//  echo '<pre>';
 // $info = curl_getinfo($ch);
 // print_r($info);
 // echo '</pre>';
  curl_close ( $ch );

  $short_url = json_decode($data);
  if(isset($short_url->error)){
      return $short_url->error->message;
  } else {
      return $short_url->shortLink;
  }

}

?>
