<?php


namespace Actualys\QasAddressValidationApi\Model;


/**
 * Class QAAuthentication
 */
class QAAuthentication {
  private $username;
  private $password;

  /**
   * @param $username
   * @param $password
   */
  public function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }
}