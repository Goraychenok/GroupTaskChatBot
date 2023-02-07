<?php
$id = $_REQUEST['data']['FIELDS_AFTER']['ID'];
$link = 'https://api.telegram.org/bot5985398035:AAHQTpy4XWOkYfUSzTL-v2KMDrY13P41t-Y/sendMessage';

$getTask = curl_init();
curl_setopt_array($getTask, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => 'https://aerokod.bitrix24.ru/rest/82/urkeup9m3sxbllxo/tasks.task.get',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(array('taskId' => $id))
));
$response = curl_exec($getTask);
$response = json_decode($response, true);
curl_close($getTask);
$taskData = $response['result']['task'];


$responsibleId = $taskData['responsible']['id'];

$getResp = curl_init();
curl_setopt_array($getResp, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HEADER => 0,
    CURLOPT_URL => 'https://aerokod.bitrix24.ru/rest/82/urkeup9m3sxbllxo/user.get',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(array('ID' => $responsibleId))
));
$respResp = curl_exec($getResp);
$respResp = json_decode($respResp, true);
curl_close($getResp);
$respTelegram = $respResp['result'][0]['UF_USR_1675756854374'];



$accomplicId = [];
$accomplic = '';


if (!empty($taskData['accomplicesData'])) {
    foreach ($taskData['accomplicesData'] as $key => $item) {
        $accomplicId[] = $key;
    }


    $getAcomplic = curl_init();
    curl_setopt_array($getAcomplic, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => 'https://aerokod.bitrix24.ru/rest/82/urkeup9m3sxbllxo/user.get',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array('ID' => $accomplicId))
    ));
    $respAcomplic = curl_exec($getAcomplic);
    $respAcomplic = json_decode($respAcomplic, true);
    curl_close($getAcomplic);
    foreach ($respAcomplic['result'] as $acompl) {
        $accomplic .= ($acompl['UF_USR_1675756854374']) ? '@'.$acompl['UF_USR_1675756854374'] : $acompl['NAME'].' '.$acompl['LAST_NAME'];
        $accomplic .= ' ';
    }
}


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


foreach($response['result'][0]['UF_USR_1675679377731'] as $key => $item) {
    if ($item == $taskData['groupId']) {

        $message = '';
        $message .= "Поставлена новая задача: '".$taskData['title']."'\n";
        $message .= "ID задачи: ".$taskData['id']."\n";
        $message .= "Постановщик: ".$taskData['creator']['name']."\n";
        $message .= "Ответственный: ".$resp = ($respTelegram) ? "@".$respTelegram : $taskData['responsible']['name'];
        $message .= "\n";
        if ($accomplic) {
            $message .= "Наблюдатели: ".$accomplic."\n";
        }
        $message .= "Ссылка: https://aerokod.bitrix24.ru/workgroups/group/".$taskData['groupId']."/tasks/task/view/".$taskData['id']."/";
        $getTask = curl_init();
        $sendMessage = curl_init();
        curl_setopt_array($sendMessage, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $link ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array('text' => $message, 'chat_id' => $response['result'][0]['UF_USR_1675679412883'][$key]))
        ));
        $telgramResponce = curl_exec($sendMessage);
        $telgramResponce = json_decode($telgramResponce, true);
        curl_close($sendMessage);
        //
    }
}




?>
