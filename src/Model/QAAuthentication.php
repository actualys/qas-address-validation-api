<?php


namespace Actualys\QasAddressValidationApi\Model;


/**
 * Class QAAuthentication
 */
class QAAuthentication {
  private $Username;
  private $Password;

  /**
   * @param $username
   * @param $password
   */
  public function __construct($username, $password) {
    $this->Username = $username;
    $this->Password = $password;
  }

  /**
   * @return mixed
   */
  public function getPassword() {
    return $this->Password;
  }

  /**
   * @param mixed $Password
   */
  public function setPassword($Password) {
    $this->Password = $Password;
  }

  /**
   * @return mixed
   */
  public function getUsername() {
    return $this->Username;
  }

  /**
   * @param mixed $Username
   */
  public function setUsername($Username) {
    $this->Username = $Username;
  }


}