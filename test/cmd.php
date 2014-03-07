<?php

include_once "PutIO.php";
include_once "KTXP.php";
include_once "Tool.php";

$v = new KTXP();
$v->make_search_sentence(array(".*Trick.*720.*", ".*Magical.*繁體.*720.*"), array("极影", "DHR"));
while(true) {
	$v->parse(20);
	$zip = $v->request_download();
	if ($zip) {
		Tool::unzip($zip, "D:\\");
		@unlink($zip);
	}
	sleep(3600);
}
?>