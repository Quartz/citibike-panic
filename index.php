<?php
date_default_timezone_set('America/New_York');
require_once(__DIR__ . "/CitiBikePanicStatusBoard.php");


$config = Array(
	"apiUrl"=>"http://appservices.citibikenyc.com/data2/stations.php",
	"stations"=>Array(
									Array("stationId"=>382,"stationName"=>"University Pl & 14th"),
                  Array("stationId"=>285,"stationName"=>"Broadway & 14th"),
									Array("stationId"=>357,"stationName"=>"11th & Broadway")
						)
   );



  $C = new CitiBike($config);
  $sbJson = $C->generateStatusBoardJson();
  if($sbJson !== FALSE){
    $response = $sbJson;
  }else{
    $response['success'] = FALSE;
    $response['error'] = "bad results from generate status board";
    $response = json_encode($response);
  }
  echo $response;

