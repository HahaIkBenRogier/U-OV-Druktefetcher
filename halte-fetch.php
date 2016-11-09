<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$bussen = array("halte" => "wijk-bij-duurstede%2Famstel", "lijn" => "41", "kleur" =>  "__kleur __kleur-1");
print_r($bussen);



$url = "https://u-ov.info/index.php/tools/apiDirect.php?apiType=dienstregeling%2Fdris-json&apiDataType=html&halte=wijk-bij-duurstede%2Famstel&is_mobile=false";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'SNGRS/0.1 +http://sngrs.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result= curl_exec($ch);
curl_close($ch);



$dom = new \DOMDocument('1.0', 'UTF-8');
// set error level
$internalErrors = libxml_use_internal_errors(true);
$dom->loadHTML($result);
$xpath = new DOMXpath($dom);
// Restore error level
libxml_use_internal_errors($internalErrors);

$new = array();
$list = array();
foreach($dom->getElementsByTagName('tr') as $element_tr ){
	$list2 = array();
	foreach($element_tr->getElementsByTagName('td') as $element_td ){
		$list3 = array();
		foreach($element_td->getElementsByTagName('div') as $element_div ){
			$list4 = array();
			foreach($element_div->getElementsByTagName('*') as $element_aterisk ){
				array_push($list4, $element_aterisk->textContent);
				if ($element_aterisk->hasAttributes()) {
					$list5 = array();
					$childs = $element_aterisk->attributes;
					foreach($childs as $i) {
						array_push($list4, $i->nodeValue);
					}
				}
				array_push($list4, $list5);
			};
			array_push($list3, $list4);
		};
		array_push($list2, $list3);
	};


	$new2 = array();
	$new2["lijn"] = $list2[0][0][0];
	$new2["kleur"] = $list2[0][0][4];
	$new2["eindbestemming"] = $list2[1][0][0];
	$new2["drukte"] = $list2[1][0][5];
	$new2["aanwezig_planning"] = $list2[3][0][0];
	$new2["aanwezig_verwacht"] = $list2[3][0][2];
	$new2["aanwezig_bevestigd"] = $list2[3][0][6];
	unset( $list2 );

	if ($new2["lijn"] == "41" && $new2["kleur"] == "__kleur __kleur-1") {
		array_push($new, $new2);
	};

};

print_r($new);


?>
