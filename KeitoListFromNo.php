<?php
require_once('simple_html_dom_custom.php');//htmlのパース用

//ヘッダー情報
header("Content-Type: application/json; charset=utf-8");
header('X-Content-Type-Options: nosniff');//IE対策(ファイル自動判別防止)

//バスナビのhtmlを取得
$html = file_get_html('http://www.busnavi-okinawa.com/map/Location/');
//id="selected"の0番目を取得(系統取得のため)
$str = str_get_html($html->find('select[id=select]', 0));
$keito = array();
for($i = 1;@$str->find('option', $i);$i++){//「選択してください」文字列を避けるため$i=1
	//系統番号、系統名を取得し配列で一時保存
	$tmp = preg_split("/\./",$str->find('option', $i)->innertext);
	//系統名を格納
	$keito[$tmp[0]]["KeitoName"] = $tmp[1];
	//系統Sidを格納
//系統SID(改行を取り除く)
	$keito[$tmp[0]]["KeitoSid"]  = str_replace(array("\r\n","\r","\n"), '', ($str->find('option', $i)->value."\n"));
	//次の系統へ(インクリメント)
}
//jsonへ変換
echo json_encode($keito/*,JSON_UNESCAPED_UNICODE*/);

?>