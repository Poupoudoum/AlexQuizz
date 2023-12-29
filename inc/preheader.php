<?php
//on empèche les phpsessid de passer par l'url
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');  // PHP >= 4.3
ini_set('session.cookie_httponly', '1');  // PHP >= 4.3
ini_set('session.use_trans_sid', '0');
ini_set('url_rewriter.tags', '');
session_start();
//avant tout, si magiQuotes n'est pas activé, on fait comme si ca l'etait!!!
if (!get_magic_quotes_gpc()) {
   function addslashes_deep($value)
   {
       $value = is_array($value) ?
                   array_map('addslashes_deep', $value) :
                   addslashes($value);
       return $value;
   }
   $_POST = array_map('addslashes_deep', $_POST);
   $_GET = array_map('addslashes_deep', $_GET);
   $_COOKIE = array_map('addslashes_deep', $_COOKIE);
   $_REQUEST = array_map('addslashes_deep', $_REQUEST);
}

define("BP", dirname(__DIR__));

include BP.'/inc/functions.php';
include BP.'/inc/Cache.php';

$head = "";


//CONFIG 
$votes = array("film" => "Le Film", "acteur" => "Acteur(tice) pincipal(e)");
$titre = "Blind Test Filmographique 2024";

?>
