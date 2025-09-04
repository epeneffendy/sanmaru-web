<?php

namespace App\Exceptions;

class BaseException extends \Exception {
  protected $details;

  public function __toString() {
    return $this->details;
  }
}
