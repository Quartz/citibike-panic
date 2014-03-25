<?php


/*
 * Pulldown and convert data from citibike API into format for Panic's status board.
 */
class CitiBike {

  public function __construct($config){
		$this->_apiUrl = $config['apiUrl'];
		$this->_stations = $config['stations'];
  }

  public function generateStatusBoardJson(){
    $apiResults = $this->_apiCall();
    if(!is_array($apiResults)){
      return FALSE;
    }
    $this->_stationsById = $this->_orderStationsById($apiResults);
    if($this->_stationsById === FALSE){
      return FALSE;
    }

    $stationsStatus = Array();
    foreach($this->_stations as $station){
      $results = $this->_getStationStatus($station['stationId']);
      $station['bikes'] = $results['bikes'];
      $station['docks'] = $results['docks'];
      $stationsStatus[] = $station;
    }

    $statusBoardJson = $this->_stationsToSB($stationsStatus);
    return $statusBoardJson;

  }

  private function _stationsToSB($stationsStatus){
    $statusBoardObj = new stdClass();
    $statusBoardObj->title = "CitiBikes";
    $statusBoardObj->datasequences = Array();

    $bikesObj = new stdClass(); 
    $bikesObj->title = "bikes";
    $bikesObj->color = "blue";
    $bikesObj->datapoints = Array();

    $docksObj = new stdClass(); 
    $docksObj->title = "docks";
    $docksObj->color = "lightGray";
    $docksObj->datapoints = Array();

    foreach($stationsStatus as $status){

      $bikeObj = new stdClass();
      $bikeObj->title = $status['stationName'];
      $bikeObj->value = $status['bikes'];
      $bikesObj->datapoints[] = $bikeObj;

      $dockObj = new stdClass();
      $dockObj->title = $status['stationName'];
      $dockObj->value = $status['docks'];
      $docksObj->datapoints[] = $dockObj;

    }


      $datapoints = Array($bikesObj,$docksObj);
      $statusBoardObj->datasequences = $datapoints;
    $O = new stdClass();
    $O->graph = $statusBoardObj;

    return json_encode($O);
  }

  private function _getStationStatus($stationId){
    $status = Array();
    $status['bikes'] = $this->_stationsById[$stationId]['availableBikes'];
    $status['docks'] = $this->_stationsById[$stationId]['availableDocks'];
    return $status;
  }

  private function _orderStationsById($apiResults){
    if(!isset($apiResults['results'])){
      return FALSE;
    }
    $results = $apiResults['results'];
    $stations = Array();
    foreach($results as $station){
      $station['stationId'] = $station['id'];
      $stations[$station['id']] = $station;

    }

    return $stations;

  }

  private function _apiCall(){

    $url = $this->_apiUrl;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($ch);
    $results = json_decode($response,TRUE);
    return $results;
  }
}
