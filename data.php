<?php 
include "inc/preheader.php";
header('content-type: text/json');

$data = array(
    "joueurs" => Cache::get('joueurs') ?? array(),
    "question" => Cache::get('question'),
    "questions" => getQuestions(),
    "points" => Cache::get('points') ?? array(),
);

foreach (Config::$votes as $v => $t) {
    $data[$v] = Cache::get($v) ?? array();
}

echo json_encode($data, JSON_PRETTY_PRINT);

