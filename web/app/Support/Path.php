<?php

namespace App\Support;

class Path {
  public string $filename;
  public string $real_path;

  public function __construct(string $filename, string $real_path)
  {
    $this->filename = $filename;
    $this->real_path = $real_path;
  }
}
