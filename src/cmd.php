<?php

require_once "PutIO.php";
require_once "KTXP.php";
require_once "Tool.php";

$title = array(
        "【听潺社】★【钻石王牌\/鑽石王牌_Ace of Diamond】【.*】【繁体】【1280X720】",
        "【Fairy Tail 妖精的尾巴\/魔導少年】【第.*話】【BIG5】【1280X720】【MP4】",
        "【極影字幕社】 ★ 白銀的意志 Argevollen.*",
        "【DHR動研字幕組&白月字幕組】\[刀劍神域II_Sword Art Online II\]\[.*\]\[繁體\]\[720P\]\[MP4\].*",
        "【極影字幕社】 ★ 斬 赤紅之瞳.*",
        "【西農YUI漢化組】★七月新番【花舞少女 Hanayamata】.*",
        "【白月字幕組】★七月新番【青春之旅\/閃爍的青春】.*",        "【動漫國字幕組】★07月新番\[女神異聞錄4 黃金版\].*",
        "【極影字幕社】★10月新番 LOG HORIZON The 2nd Series \/ 記錄的地平線 第二季 第.*話 BIG5 MP4 720p",
        "【极影字幕社】★ 战斗少女选择者WIXOSS \/ selector spread WIXOSS 第.*集 GB_CN 720p MP4",
        "【极影字幕社】 ★ 寻找失去的未来 ushinawareta-mirai 第.*话GB MP4 720P",
        "【動漫國字幕組】★10月新番\[魔彈之王與戰姬\]\[.*\]\[720P\]\[繁體\]\[MP4\]",
        "【極影字幕社】 ★10月新番 CROSSANGE 天使與龍的輪舞_CROSSANGE 第.*話 BIG5 720P MP4",
        "【极影字幕社】 ★10月新番 \[境界触发者\]\[World Trigger\]\[.*\] GB MP4_720P",
        "【RH字幕組】狼少女與黑王子\[Ookami_Shoujo_to_Kuro_Ouji\]\[.*\]\[BIG5\]\[720P\]\[MP4\]",
        "【DHR動研字幕組】\[電波少女與錢仙大人\/銀仙_Gugure! Kokkuri-san\]\[.*\]\[繁體\]\[720P\]\[MP4\]",
        "【DHR&動漫國\&茉語月夜】\[天體的秩序\/天體的方式 Sora-no-Method\]\[.*\]\[繁體\]\[720P\]\[MP4\]",
        "【DHR肉片小组\&轻之国度字幕组】\[灰色的果实_LE FRUIT DE LA GRISAIA\/Grisaia no Kajitsu\]\[.*\]\[简体\]\[720P\]\[MP4\]",
        "【極影字幕社】★10月新番 Ookami Shoujo to Kuro Ouji \/ 狼少女與黑王子 第.*話 BIG5 MP4 720p",
        "【極影字幕社】 ★10月新番 【巴哈姆特之怒GENESIS】【Shingeki no Bahamut GENESIS】【.*】 BIG5 MP4_720P",
        "【動漫國字幕組】★10月新番\[七原罪\/七大罪\]\[.*\]\[720P\]\[繁體\]\[MP4\]",
        "【兔子字幕社】【10月新番】日常系的異能戰鬥_Inou Battle wa Nichijou-kei no Naka de【.*】【1280X720】【繁體】【BIG5_MP4】",
        "【極影字幕社】 ★ 甘城輝煌樂園救世主 Amagi Brilliant Park第.*話BIG5 MP4 720P",
        "【KNA字幕組】【魔道書7使者 七人魔法使 Trinity Seven】\[.*\]\[1280x720\]\[MP4_PC&PSV兼容\]\[繁體\]",
        "【極影字幕社】 ★10月新番 【棺姬嘉依卡 2】【Hitsugi no Chaika AVENGING BATTLE】【.*】 BIG5 MP4_720P",
        "【白月字幕組】★十月新番【寄生獸 生命的準則】第.*話 繁體 720P",
        "\[輕之國度字幕組\]\[10月新番\]\[我，要成為雙馬尾\]\[第.*話\]\[BIG5\]\[720P\]\[MP4\]",
        "【極影字幕社】 ★10月新番 【心理測量者2】【PSYCHO-PASS 2】【.*】BIG5 MP4_720P",
        "【Dymy字幕組】【Fate\/stay night \- Unlimited Blade Works】【第.*話】【BIG5】【1280X720】【MP4】",
        "【Dymy字幕組】【四月是你的謊言】【.*】【BIG5】【1280X720】【MP4】",
        "【極影字幕社】 ★10月新番 【火星異種】【TERRA FORMARS ANNEX 1】【.*】 BIG5 MP4_720P",
        "【极影字幕社&看板娘团队&炸天团队】 ★10月新番 结城友奈是勇者 .* GB 720P MP4",
        "【極影字幕社】★4月新番 蟲師 續章_mushishi 第.*話 BIG5 720P MP4",
        "【DHR&千夏&茉語月夜】\[女友伴身邊\/臨時女友_Girl Friend BETA\]\[.*\]\[繁體\]\[720P\]\[MP4\]",
        "【極影字幕社】★10月新番 SHIROBAKO \/ 白箱 第.*話 BIG5 720P MP4",
        "\[澄空学园\] 大图书馆的牧羊人 第.*话 MP4 720p",
        "【動漫國字幕組】★07月新番\[刀劍神域Ⅱ\/Sword Art Online Ⅱ\]\[.*\]\[720P\]\[繁體\]\[MP4\]"
        // ".*\[儘管如此世界依然美麗\]\[.*\]\[1280x720\]\[繁體\]"
    );

$publisher = array(
        "极影", 
        "極影",
        "DHR",
        "听潺社",
        "轻之国度",
        "輕之國度",
        "(summer1278|blby)",
        "W-ZONE",
        "白恋动漫",
        "动音漫影",
        "动音漫影字幕组",
        "悠哈C9字幕社",
        "動漫國字幕組",
        "动漫国字幕组",
        "DA",
        "动漫国字幕组",
        "澄空学园",
        "异域动漫",
        "白月字幕组",
        "西农YUI汉化组",
        "KNA字幕組"
    );

$v = new KTXP();
$v->make_search_sentence($title, $publisher);
while(true) {
    $v->parse(3);
    $zip = $v->request_download();
    if ($zip) {
        Tool::unzip($zip, "F:\\anime");
        @unlink($zip);
    }
    unset($zip);
    sleep(1);
}
?>