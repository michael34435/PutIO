<?php

require_once "PutIO.php";
require_once "KTXP.php";
require_once "Tool.php";

$title = array(
        ".*Trick.*720.*", 
        ".*Magical.*繁體.*720.*", 
        ".*Strike.*BIG5.*720.*",
        ".*NO-RIN.*繁體.*720.*",
        ".*世界征服.*繁體.*720P",
        ".*Daiya.*BIG5.*720P",
        ".*Ace.*720.*繁體",
        ".*魔女的使命.*BIG5.*720",
        ".*信長之槍.*720.*繁體",
        ".*野良神.*BIG5.*720",
        ".*Wizard.*BIG5.*720",
        ".*屬性同好會.*BIG5.*720",
        ".*Tokyo Ravens.*BIG5.*720",
        ".*Inari.*BIG5.*720",
        ".*未確認進行式.*BIG5.*720",
        ".*中二病也要談戀愛! 戀_Chuunibyou demo Koi ga Shitai!Ren.*BIG5.*720p",
        ".*Kill-La-Kill.*BIG5.*720",
        ".*1月新番 銀之匙_silver Spoon 第二季.*720.*BIG5",
        ".*1月新番 【鬼灯的冷彻】【Hoozuki no Reitetsu】.*BIG5.*720",
        ".*\[魔法戰爭_Magical Warfare\].*繁體.*720",
        ".*【第一神拳 第三季 Rising Hajime no Ippo - The Fighting! Rising】.*BIG5.*720",
        ".*10月新番 LOG HORIZON.*BIG5.*720",
        ".*偽戀 Nisekoi.*BIG5.*720",
        ".*\[農林_NO-RIN\].*繁體.*720"
    );

$publisher = array(
        "极影", 
        "DHR",
        "听潺社",
        "轻之国度",
        "summer1278",
        "W-ZONE",
        "白恋动漫",
        "动音漫影字幕组"
    );

$v = new KTXP();
$v->make_search_sentence($title, $publisher);
while(true) {
    $v->parse();
    $zip = $v->request_download();
    if ($zip) {
        Tool::unzip($zip, "I:\\anime");
        @unlink($zip);
    }
    unset($zip);
    sleep(1);
}
?>