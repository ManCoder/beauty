<?php

/*настройки*/

$mobile_tokens = array( // перечисленные через запятую токены доступа мобилы
        'test123',
	'Jd1d123dssa1231ds112essas2j1dj21d12jd2',
	'Jd1d123dssa1231ds112essas2j1dj21d12jd'
);
$yandex_url = 'https://api.content.market.yandex.ru/'; // url api яндекса
$yandex_auth_code = 'KdaJfLduuf2lDTzgedj4IQ039UNVx1'; //код авторизации яндекс
/*--------*/


/*ip-адрес клиента*/
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = '&remote_ip='.$_SERVER['REMOTE_ADDR'];
}
/*--------*/


/*код скрипта*/

$is_auth = false;
$host = parse_url($yandex_url);

	if (isset($_REQUEST['apptoken'])) {
		foreach ($mobile_tokens as $v)
			if (strtoupper($v)===strtoupper($_REQUEST['apptoken'])) {
				$is_auth = true;
				break;
			}
		if ($is_auth) {
			$url = $yandex_url.$_REQUEST['q'];
			unset($_REQUEST['apptoken'],$_REQUEST['q']);
			$opts = array(
				'http' => array(
					'method' => strtoupper($_SERVER['REQUEST_METHOD']),
					'header' => "Content-Type: application/json"."\r\nHost: ".$host['host']."\r\nAccept: */*\r\nAuthorization: $yandex_auth_code\r\n" 
				)
			);
			if ($_POST)
				$opts['http']['content'] = http_build_query($_REQUEST);
			else
				$url .= '?'.http_build_query($_REQUEST).$ip;
			$res = @file_get_contents($url, false, stream_context_create($opts));
			header($http_response_header[0]);	
			if ($res)
				echo $res;
		} else {
			echo json_encode(array('errors'=>array('not mobile auth')));
		}
	} else {
		echo json_encode(array('errors'=>array('not mobile auth')));
	}

?>

