<?php

$groupID = $_POST['group_id'];
$chatID  = $_POST['chat_id'];

$getAcc = curl_init();
curl_setopt_array($getAcc, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => 'https://aerokod.bitrix24.ru/rest/82/urkeup9m3sxbllxo/user.get',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(array('ID' => 200))
));
$response = curl_exec($getAcc);
$response = json_decode($response, true);
curl_close($getAcc);

$elem = $response['result'][0];
array_push($elem['UF_USR_1675679377731'], $groupID);
array_push($elem['UF_USR_1675679412883'], $chatID);

$setAcc = curl_init();
curl_setopt_array($setAcc, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => 'https://aerokod.bitrix24.ru/rest/82/urkeup9m3sxbllxo/user.update',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(array('ID' => $elem['ID'], 'UF_USR_1675679377731' => $elem['UF_USR_1675679377731'], 'UF_USR_1675679412883'=> $elem['UF_USR_1675679412883'], 'params' => ['REGISTER_SONET_EVENT' => 'Y']))
));
$response = curl_exec($setAcc);
curl_close($setAcc);




?>