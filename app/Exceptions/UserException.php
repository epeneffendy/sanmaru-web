<?php

namespace App\Exceptions;

class UserException extends BaseException {
  public $additionalInfo;
  
  public function __construct($details) {
      $this->details = "[User error] $details";
      parent::__construct($this->details);
  }
}
