<?php
$json_tmp = file_get_contents("http://localhost/api/web/traffic/tmp_data");
$obj_tmp = json_decode($json_tmp);
$sim1_players = $obj_tmp->response[0]->players.' / '.$obj_tmp->response[0]->maxplayers;
?>
