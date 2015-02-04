<?php

namespace Actualys\QasAddressValidationApi\Model;


/**
 * Class DisplayList
 */
class DisplayList {
  public $sMoniker;
  public $sCommand;
  public $sPreview;
  public $sPostcode;
  public $sScore;

  /**
   * @return mixed
   */
  public function getSCommand() {
    return $this->sCommand;
  }

  /**
   * @param mixed $sCommand
   */
  public function setSCommand($sCommand) {
    $this->sCommand = $sCommand;
  }

  /**
   * @return mixed
   */
  public function getSMoniker() {
    return $this->sMoniker;
  }

  /**
   * @param mixed $sMoniker
   */
  public function setSMoniker($sMoniker) {
    $this->sMoniker = $sMoniker;
  }

  /**
   * @return mixed
   */
  public function getSPostcode() {
    return $this->sPostcode;
  }

  /**
   * @param mixed $sPostcode
   */
  public function setSPostcode($sPostcode) {
    $this->sPostcode = $sPostcode;
  }

  /**
   * @return mixed
   */
  public function getSPreview() {
    return $this->sPreview;
  }

  /**
   * @param mixed $sPreview
   */
  public function setSPreview($sPreview) {
    $this->sPreview = $sPreview;
  }

  /**
   * @return mixed
   */
  public function getSScore() {
    return $this->sScore;
  }

  /**
   * @param mixed $sScore
   */
  public function setSScore($sScore) {
    $this->sScore = $sScore;
  }



}
