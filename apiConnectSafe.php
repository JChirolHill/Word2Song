<?php
  require('vlucas/phpdotenv');
  $dotenv = Dotenv\Dotenv::create(__DIR__, 'myconfig');
  $dotenv->load();

  var_dump($dotenv);

  $API_KEY_MUSIC = $_ENV["API_KEY_MUSIC"];
  $API_ENDPOINT_MUSIC = "https://api.musixmatch.com/ws/1.1/";
  $url_params = [];
  $url_params['apikey'] = $API_KEY_MUSIC;

  function call_musixmatch_api($REQUEST_TYPE, $API_URL, $url_params) {
    // echo $API_URL;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $REQUEST_TYPE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($url_params));

    $headers = array();
    $headers[] = "Accept-Language: en_US";
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //For https
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //For https
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

    return $result;
  }

  $action = isset($_POST['action']) ? $_POST['action'] : '';
  if($action == 'wordQuery') {
    // set params for API call based on front end specifications
    $url_params['q_lyrics'] = $_POST['word'];
    $url_params['f_lyrics_language'] = $_POST['lang'];
    $url_params['s_track_rating'] = 'DESC';
    $url_params['s_track_release_date'] = 'DESC';
    $url_params['has_lyrics'] = 1;
    $url_params['instrumental'] = 0;

    // get the result and decode
    $result = '';
    $status_code = 0;
    do { // call until 200 status code
      $result = json_decode(call_musixmatch_api("GET", $API_ENDPOINT_MUSIC . "track.search", $url_params), TRUE)['message'];
      $status_code = $result['header']['status_code'];
      // echo 'Status code: ' . $status_code;
    } while($status_code != 200);
    $result = $result['body']['track_list'];

    $resultTracks = [];
    foreach($result as $trackID => $track) {
      $resultTracks[$trackID] = [];
      $resultTracks[$trackID]['track_name'] = $track['track']['track_name'];
      $resultTracks[$trackID]['track_rating'] = $track['track']['track_rating'];
      $resultTracks[$trackID]['album_name'] = $track['track']['album_name'];
      $resultTracks[$trackID]['artist_name'] = $track['track']['artist_name'];
      $resultTracks[$trackID]['lyrics_link'] = $track['track']['track_share_url'];
      $resultTracks[$trackID]['has_subtitles'] = $track['track']['has_subtitles'];
    }
    http_response_code(200);
    header("Content-Type: application/json");
    echo json_encode($resultTracks);
  }
