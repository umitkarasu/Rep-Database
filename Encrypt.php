<?php
  class Encrypt {

    public function crypt($tables, $fields, $excluded = []) {

      $tableRows = [];

      foreach ($tables as $rows) {

        $encrypted = [];

        foreach ($rows as $key => $value) {

          $chars = str_split($value);
          $crypted = '';

          if(in_array($key, $fields)) {

            foreach ($chars as $char) {

              if(!in_array($char, $excluded)) {

                $crypted .= $this->_randCharGen($char);

              } else {

                $crypted .= $char;

              }

            }

            $encrypted[$key] = $crypted;

          } else {

            $encrypted[$key] = $value;

          }
        }

        $tableRows[] = $encrypted;
      }

      return $tableRows;

    }

    private function _randCharGen($char) {

      $chars = str_split("abcdefghijklmnopqrstuvwxyz");
      $numbers = str_split("0123456789");

      if(is_numeric($char)) {

       return array_rand($numbers);

      }


      if(ctype_upper($char)) {

        return strtoupper($chars[array_rand($chars)]);

      }

      return $chars[array_rand($chars)];
  }

}

?>
