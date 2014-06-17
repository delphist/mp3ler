<?php
$count = 2;

try {
	for($i = 0; $i < $count; $i++) {
		if($_SERVER['HTTP_HOST'] == 'val.fm') {
			require __DIR__ . '/MkhojAd.php';
			echo '<br>';
		}
		elseif($_SERVER['HTTP_HOST'] == 'val.mobi') {
			require __DIR__ . '/MkhojAd3.php';
			echo '<br>';
		}
		elseif($_SERVER['HTTP_HOST'] == 'ru.val.fm') {
			require __DIR__ . '/MkhojAd2.php';
			echo '<br>';
		}
	}
} catch(Exception $e) {
	echo '<!--' . $e . '-->';
	echo '<!-- no ads -->';
}