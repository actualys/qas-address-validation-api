<?php

namespace Actualys\QasAddressValidationApi\Model;


/**
 * Class DisplayList
 */
class DisplayList {
  public $moniker;
  public $command;
  public $preview;
  public $postcode;
  public $score;

  /**
   * @return mixed
   */
  public function getCommand() {
    return $this->command;
  }

  /**
   * @param mixed $command
   */
  public function setCommand($command) {
    $this->command = $command;
  }

  /**
   * @return mixed
   */
  public function getMoniker() {
    return $this->moniker;
  }

  /**
   * @param mixed $moniker
   */
  public function setMoniker($moniker) {
    $this->moniker = $moniker;
  }

  /**
   * @return mixed
   */
  public function getPostcode() {
    return $this->postcode;
  }

  /**
   * @param mixed $postcode
   */
  public function setPostcode($postcode) {
    $this->postcode = $postcode;
  }

  /**
   * @return mixed
   */
  public function getPreview() {
    return $this->preview;
  }

  /**
   * @param mixed $preview
   */
  public function setPreview($preview) {
    $this->preview = $preview;
  }

  /**
   * @return mixed
   */
  public function getScore() {
    return $this->score;
  }

  /**
   * @param mixed $score
   */
  public function setScore($score) {
    $this->score = $score;
  }


}
