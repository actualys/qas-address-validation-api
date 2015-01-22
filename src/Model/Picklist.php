<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Model\QuickAddress;

/**
 * Class Picklist
 */
class Picklist {
  public $total = 0;
  public $picklistMoniker = "";
  public $prompt = "No Items";
  public $items = array();
  public $isTimeout;
  public $isMaxMatches;
  public $overThreshold;
  public $largePotential;
  public $moreOtherMatches;
  public $autoStepinSafe;
  public $autoStepinPastClose;
  public $autoFormatSafe;
  public $autoFormatPastClose;

  /**
   * @param $result
   * @throws Exception
   */
  public function __construct($result) {
    if (QuickAddress::check_soap(
        $result
      ) != NULL && ($picklist = $result->qaPicklist) != NULL
    ) {
      $this->total               = $picklist->Total;
      $this->prompt              = $picklist->Prompt;
      $this->picklistMoniker     = $picklist->FullPicklistMoniker;
      $this->isTimeout            = $picklist->Timeout;
      $this->isMaxMatches         = $picklist->MaxMatches;
      $this->overThreshold       = $picklist->OverThreshold;
      $this->largePotential      = $picklist->LargePotential;
      $this->moreOtherMatches    = $picklist->MoreOtherMatches;
      $this->autoStepinSafe      = $picklist->AutoStepinSafe;
      $this->autoStepinPastClose = $picklist->AutoStepinPastClose;
      $this->autoFormatSafe      = $picklist->AutoFormatSafe;
      $this->autoFormatPastClose = $picklist->AutoFormatPastClose;

      if (!isset($picklist->picklistEntry)) {
        $this->items = array();
      }

      elseif (is_array($picklist->picklistEntry)) {
        $this->items = $picklist->picklistEntry;
      }

      else {
        $this->items = array($picklist->picklistEntry);
      }
    }
  }

  /**
   * @return bool
   */
  function isAutoStepinSingle() {
    return ($this->total == 1 &&
      $this->items[0]->canStep &&
      !$this->items[0]->information);
  }

  /**
   * @return bool
   */
  function isAutoFormatSingle() {
    return ($this->total == 1 &&
      $this->items[0]->fullAddress &&
      !$this->items[0]->information);
  }
}

