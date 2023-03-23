<?php

include_once "./get_file.php";
include_once "./write_file.php";
include_once "./delete_file.php";

$base_url = basename(__DIR__);
$req_url = $_SERVER["REQUEST_URI"];
$url = trim(preg_replace("/.*$base_url/", "", $req_url), "/");
$url = explode("?", $url)[0];

$path = "/drive/$url";
$path = urldecode($path);

$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];

if ($REQUEST_METHOD == "GET") {
  $exists = file_exists(".$path");
  $isdir = is_dir(".$path");
  if ($isdir) {
    $files = list_files($path);
    echo $files;
  } elseif ($exists) {
    get_file($path);
  } else {
    http_response_code(404);
  }
} elseif ($REQUEST_METHOD == "POST") {
  $exists = file_exists(".$path");
  $isdir = is_dir(".$path");

  if ($exists || $isdir) {
    http_response_code(409);
  } else {
    $parts = explode("/", $url);
    $file = file_get_contents("php://input");

    write_file(".$path", $file);
  }
} elseif ($REQUEST_METHOD == "DELETE") {
  $exists = file_exists(".$path");
  $isdir = is_dir(".$path");

  if (!$exists || $isdir) {
    http_response_code(404);
  } else {
    delete_file(".$path");
  }
}
