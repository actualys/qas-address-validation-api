<?php

namespace Actualys\QasAddressValidationApi\Model;


/**
 * Class QAGetAddress
 */
class QAGetAddress {
  /**
   * @var string
   */
  public $layout;

  /**
   * @var string
   */
  public $moniker;

  /**
   * @var string
   */
  public $localisation;

  /**
   * @return string
   */
  public function getLayout() {
    return $this->layout;
  }

  /**
   * @param string $layout
   */
  public function setLayout($layout) {
    $this->layout = $layout;
  }

  /**
   * @return string
   */
  public function getLocalisation() {
    return $this->localisation;
  }

  /**
   * @param string $localisation
   */
  public function setLocalisation($localisation) {
    $this->localisation = $localisation;
  }

  /**
   * @return string
   */
  public function getMoniker() {
    return $this->moniker;
  }

  /**
   * @param string $moniker
   */
  public function setMoniker($moniker) {
    $this->moniker = $moniker;
  }



}