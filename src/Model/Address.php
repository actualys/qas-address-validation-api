<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Model\QuickAddress;

/**
 * Class Address
 */
class Address {

  public $AddressLine;
  public $Overflow;
  public $Truncated;
  public $DPVStatus;

  /**
   * @param $tQAAddress
   */
  public function __construct($tQAAddress) {

    $this->atAddressLines = $tQAAddress->AddressLine;
    $this->bOverflow      = $tQAAddress->Overflow;
    $this->bTruncated     = $tQAAddress->Truncated;
    $this->sDpvStatus     = $tQAAddress->DPVStatus;

    if (!is_array($this->atAddressLines)) {
      $this->atAddressLines = array($this->atAddressLines);
    }

    for ($i = 0; $i < sizeof($this->atAddressLines); $i++) {
      if (isset($this->atAddressLines[$i]->DataplusGroup) && !is_array(
          $this->atAddressLines[$i]->DataplusGroup
        )
      ) {
        $this->atAddressLines[$i]->DataplusGroup = array($this->atAddressLines[$i]->DataplusGroup);
      }
    }
  }
}
