<?php

function delete_file(string $file_path) {
  $success = unlink($file_path);

  if (!$success) {
    http_response_code(400);
  }
}
