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
	
	//系統番号
	$keito_no = $tmp[0];
	//系統名
	$keito_name = $tmp[1];
	//系統SID(改行を取り除く)
	$keito_sid = str_replace(array("\r\n","\r","\n"), '', ($str->find('option', $i)->value."\n"));
	
	//系統ごとに連想配列を作り、全系統の配列に格納
	array_push($keito, array("KeitoNo"=> $keito_no, "KeitoName"=>$keito_name, "KeitoSid"=>$keito_sid));
	
	//系統名を格納
	//$keito[$tmp[0]]["KeitoName"] = $tmp[1];
	//系統Sidを格納
	//$keito[$tmp[0]]["KeitoSid"] = $str->find('option', $i)->value."\n";
	//次の系統へ(インクリメント)
}

//表示
/*echo("<pre>");
for($i=0; $i < count($keito); $i++){
	echo(sprintf("%d:%s[%s]\n", $keito[$i]["KeitoNo"], $keito[$i]["KeitoName"], $keito[$i]["KeitoSid"]));
}
echo("</pre>");
*/
//jsonへ変換
echo json_encode($keito/*,JSON_UNESCAPED_UNICODE*/);

?>