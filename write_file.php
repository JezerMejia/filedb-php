<?php

function write_file(string $file_path, string $file_contents) {
  $written = file_put_contents($file_path, $file_contents);

  if ($written === false) {
    http_response_code(400);
  }
}
