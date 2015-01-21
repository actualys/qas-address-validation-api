<?php

/**
 * Class QAQueryHeader
 */
class QAQueryHeader {
  private $QAAuthentication;
  private $Security;

  /**
   * @param $username
   * @param $password
   */
  public function __construct($username, $password) {
    $this->QAAuthentication = new QAAuthentication($username, $password);
    $this->Security         = NULL;
  }
}