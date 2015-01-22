<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Model\QuickAddress;
/**
 * Class Address
 */
class Address {

  public $addressLines;
  public $overflow;
  public $truncated;
  public $dpvStatus;

  /**
   * @param $tQAAddress
   */
  public function __construct(QuickAddress $qAddress) {
    $this->addressLines = $qAddress->addressLine;
    $this->overflow      = $qAddress->overflow;
    $this->truncated     = $qAddress->truncated;
    $this->dpvStatus     = $qAddress->dpvStatus;

    if (!is_array($this->addressLines)) {
      $this->addressLines = array($this->addressLines);
    }

    for ($i = 0; $i < sizeof($this->addressLines); $i++) {
      if (isset($this->addressLines[$i]->dataplusGroup) && !is_array(
          $this->addressLines[$i]->dataplusGroup
        )
      ) {
        $this->addressLines[$i]->dataplusGroup = array($this->addressLines[$i]->dataplusGroup);
      }
    }
  }
}
