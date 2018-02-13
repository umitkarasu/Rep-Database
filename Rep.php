<?php
  require_once 'DB.php';
  require_once 'Encrypt.php';

  $json = file_get_contents('builds/' . 'rep' . '.json');
  $info = json_decode($json, true);

  print_r($info);
  $database = DB::getInstance($info['db']);

  $crypto = new Encrypt();
  $newTable = $info['db']['new_name'];


  $database->createDB($newTable);

  $tables = $info['tables'];
  print_r($tables);
  foreach ($tables as $key => $value) {

    $database->find($key);
    $fields = $tables[$key];
    $results = $database->results();
    $encryptedResults = $crypto->crypt($results, $fields, $info['exclude_chars'] );
    $database->copyTableSchema($key, $newTable);
    $database->insert($key, $encryptedResults, $newTable);

  }

?>
