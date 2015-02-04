<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Model\QuickAddress;

/**
 * Class Picklist
 */
class Picklist {
  public $iTotal = 0;
  public $sPicklistMoniker = "";
  public $sPrompt = "No Items";
  public $atItems = array();
  public $isTimeout;
  public $isMaxMatches;
  public $bOverThreshold;
  public $bLargePotential;
  public $bMoreOtherMatches;
  public $bAutoStepinSafe;
  public $bAutoStepinPastClose;
  public $bAutoFormatSafe;
  public $bAutoFormatPastClose;

  /**
   * @param $result
   * @throws \Exception
   */
  public function __construct($result) {
    if (QuickAddress::check_soap(
        $result
      ) != NULL && ($tPicklist = $result->QAPicklist) != NULL
    ) {

      $this->iTotal               = $tPicklist->Total;
      $this->sPrompt              = $tPicklist->Prompt;
      $this->sPicklistMoniker     = $tPicklist->FullPicklistMoniker;
      $this->isTimeout            = $tPicklist->Timeout;
      $this->isMaxMatches         = $tPicklist->MaxMatches;
      $this->bOverThreshold       = $tPicklist->OverThreshold;
      $this->bLargePotential      = $tPicklist->LargePotential;
      $this->bMoreOtherMatches    = $tPicklist->MoreOtherMatches;
      $this->bAutoStepinSafe      = $tPicklist->AutoStepinSafe;
      $this->bAutoStepinPastClose = $tPicklist->AutoStepinPastClose;
      $this->bAutoFormatSafe      = $tPicklist->AutoFormatSafe;
      $this->bAutoFormatPastClose = $tPicklist->AutoFormatPastClose;

      if (!isset($tPicklist->PicklistEntry)) {
        $this->atItems = array();
      }

      elseif (is_array($tPicklist->PicklistEntry)) {
        $this->atItems = $tPicklist->PicklistEntry;
      }

      else {
        $this->atItems = array($tPicklist->PicklistEntry);
      }
    }
  }


  /**
* @return bool
   */
  public function isAutoStepinSingle() {
    return ($this->iTotal == 1 &&
      $this->atItems[0]->CanStep &&
      !$this->atItems[0]->Information);
  }

  /**
   * @return bool
   */
  public function isAutoFormatSingle() {
    return ($this->iTotal == 1 &&
      $this->atItems[0]->FullAddress &&
      !$this->atItems[0]->Information);
  }
}

