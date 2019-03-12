<?php
  $API_KEY_MUSIC = "2f2ccd7f5400d05c25749025de23a36a";
  $API_ENDPOINT_MUSIC = "https://api.musixmatch.com/ws/1.1/";
  $url_params = [];
  $url_params['apikey'] = $API_KEY_MUSIC;

  $API_KEY_YOUTUBE = "AIzaSyCbnOCG6jyaC7ID3BCr_BattXQa7wofKcA";
  $API_ENDPOINT_YOUTUBE = "";

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

  // function call_youtube_api($service, $part, $params) {
  //   $params = array_filter($params);
  //   $response = $service->videos->listVideos(
  //       $part,
  //       $params
  //   );
  //
  //   print_r($response);
  // }
  //
  // call_youtube_api($service,
  //   'snippet,contentDetails,statistics',
  //   array('id' => 'Ks-_Mh1QhMc'));

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
      // $resultTracks[$trackID] = array('track_name' => $track['track']['track_name'],
      //                                 'track_rating' => $track['track']['track_rating'],
      //                                 'album_name' => $track['track']['album_name'],
      //                                 'artist_name' => $track['track']['artist_name'],
      //                                 'lyrics_link' => $track['track']['track_share_url'],
      //                                 'has_subtitles' => $track['track']['has_subtitles']);
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
  else {
    // send back error message that no/incorrect action provided
  }

  // function searchListByKeyword($service, $part, $params) {
  //   $params = array_filter($params);
  //   $response = $service->search->listSearch(
  //       $part,
  //       $params
  //   );
  //
  //   print_r($response);
  // }
  // searchListByKeyword($service, 'snippet', array('maxResults' => 25, 'q' => 'surfing', 'type' => ''));

// testing the API
  // $url_params['chart_name'] = 'top';
  // $url_params['page'] = 1;
  // $url_params['page_size'] = 5;
  // $url_params['country'] = 'it';
  // $url_params['f_has_lyrics'] = 1;
  // echo "<pre>";
  // echo print_r(json_decode(call_musixmatch_api("GET", $API_ENDPOINT . "chart.tracks.get", $url_params), TRUE));
  // echo "</pre>";

  // $url_params['q_track'] = "Transatlanticism";


  // $url_params['q_lyrics'] = "meri";
  // $url_params['f_lyrics_language'] = 'hi';
  // $url_params['s_track_rating'] = 'DESC';
  // $url_params['s_track_release_date'] = 'DESC';
  // $url_params['has_lyrics'] = 1;
  // $url_params['instrumental'] = 0;
  //
  // // get the result and decode
  // $result = '';
  // $status_code = 0;
  // do { // call until 200 status code
  //   $result = json_decode(call_musixmatch_api("GET", $API_ENDPOINT_MUSIC . "track.search", $url_params), TRUE)['message'];
  //   $status_code = $result['header']['status_code'];
  //   // echo '<br />Status code: ' . $status_code;
  // } while($status_code != 200);
  // $result = $result['body']['track_list'];
  //
  // $resultTracks = [];
  // foreach($result as $trackID => $track) {
  //   $resultTracks[$trackID] = array('track_name' => $track['track']['track_name'],
  //                                   'track_rating' => $track['track']['track_rating'],
  //                                   'album_name' => $track['track']['album_name'],
  //                                   'artist_name' => $track['track']['artist_name'],
  //                                   'lyrics_link' => $track['track']['track_share_url'],
  //                                   'has_subtitles' => $track['track']['has_subtitles']);
  // }
  //
  //
  // echo "Printing_R result tracks: <br />";
  // // echo json_encode($resultTracks);
  // header("Content-Type: application/json");
  // // print_r($resultTracks);
  //
  // // header("Content-Type: application/json");
  // $resultTracksEncoded = json_encode($resultTracks);
  //
  // echo "Decoding tracks: <br />";
  // $resultTracksDecoded = json_decode($resultTracksEncoded, TRUE);
  // foreach($resultTracksDecoded as $trackID => $track) {
  //   echo $trackID . ") " . $track['track_name'] . "<br />";
  // }
