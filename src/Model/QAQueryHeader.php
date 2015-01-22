<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Model\QAAuthentication;

/**
 * Class QAQueryHeader
 */
class QAQueryHeader {
  private $qaAuthentication;
  private $security;

  /**
   * @param $username
   * @param $password
   */
  public function __construct($username, $password) {
    $this->qaAuthentication = new QAAuthentication($username, $password);
    $this->security         = NULL;
  }
}