<?php

namespace Actualys\QasAddressValidationApi\Model;

/**
 * Class SearchResult
 */
class SearchResult {
  public $picklist;
  public $address;
  public $verifyLevel;

  /**
   * @param $result
   * @throws Exception
   */
  public function __construct($result) {
    if (QuickAddress::check_soap($result) != NULL) {
      if (isset($result->qaPicklist)) {
        $this->picklist = new Picklist($result);
      }

      if (isset($result->qaAddress)) {
        $this->address = new FormattedAddress($result);
      }

      $this->verifyLevel = $result->verifyLevel;
    }
  }
}
