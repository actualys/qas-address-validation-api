<?php

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
}