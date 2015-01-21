<?php


/**
 * Class SearchResult
 */
class SearchResult {
  public $picklist;
  public $address;
  public $sVerifyLevel;

  /**
   * @param $result
   * @throws Exception
   */
  public function __construct($result) {
    if (QuickAddress::check_soap($result) != NULL) {
      if (isset($result->QAPicklist)) {
        $this->picklist = new Picklist($result);
      }

      if (isset($result->QAAddress)) {
        $this->address = new FormattedAddress($result);
      }

      $this->sVerifyLevel = $result->VerifyLevel;
    }
  }
}
