<?php

require_once "PutIO.php";
require_once "KTXP.php";
require_once "Tool.php";

$v = new KTXP();
$v->make_search_sentence(array(".*Trick.*720.*", ".*Magical.*繁體.*720.*", ".*Strike.*BIG5.*720.*"), array("极影", "DHR"));
while(true) {
    $v->parse(2);
    $zip = $v->request_download();
    if ($zip) {
        Tool::unzip($zip, "D:\\");
        @unlink($zip);
    }
    sleep(1);
}
?>