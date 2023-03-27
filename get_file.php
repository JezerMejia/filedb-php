<?php

function list_files(string $path): string {
  $pretty_path = str_replace("/drive", "", $path);
  $path = trim($path, "/");
  $files = "{ \"path\": \"$pretty_path\", \"files\": [";
  if ($handle = opendir("./$path")) {
    while (false !== ($entry = readdir($handle))) {
      if (str_starts_with($entry, ".")) {
        continue;
      }
      $file_path = "/$path/$entry";
      $isdir = is_dir(".$file_path") ? "true" : "false";
      $mime_type = mime_content_type(".$file_path");
      $file = "{ \"name\": \"$entry\", \"path\": \"$pretty_path$entry\", \"mime\": \"$mime_type\", \"is_dir\": $isdir }";

      $files .= "$file,";
    }
    $files = rtrim($files, ",");

    closedir($handle);
  } else {
    http_response_code(400);
    exit();
  }
  $files .= "]}";

  return $files;
}

function get_file(string $path) {
  header("Content-Type: " . mime_content_type("./$path"));
  header('Content-Disposition: attachment; filename="' . basename($path) . '"');
  header("Content-Length: " . filesize(".$path"));
  readfile(".$path");
}
