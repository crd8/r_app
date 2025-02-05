<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('generate_uuid')) {
  function generate_uuid() {
    if (function_exists('com_create_guid')) {
      return trim(com_create_guid(), '{}');
    } else {
      mt_srand((double)microtime()*10000);
      $charid = strtoupper(md5(uniqid(rand(), true)));
      $hyphen = chr(45);
      $uuid = substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid, 12, 4).$hyphen
        .substr($charid, 16, 4).$hyphen
        .substr($charid, 20, 12);
      return $uuid;
    }
  }
}
?>