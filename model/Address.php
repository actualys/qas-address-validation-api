<?php


/**
 * Class Address
 */
class Address {
  public $atAddressLines;
  public $bOverflow;
  public $bTruncated;
  public $sDpvStatus;

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
