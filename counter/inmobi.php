<?php
$count = 11;

try {
	for($i = 0; $i < $count; $i++) {
			require __DIR__ . '/MkhojAd.php';
			echo '<br>';
	}
} catch(Exception $e) {
	echo '<!--' . $e . '-->';
	echo '<!-- no ads -->';
}