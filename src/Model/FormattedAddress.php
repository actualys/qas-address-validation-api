<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Model\QuickAddress;
use Actualys\QasAddressValidationApi\Model\Address;

/**
 * Class FormattedAddress
 */
class FormattedAddress
  extends Address {
  /**
   * @param $result
   * @throws Exception
   */
  public function __construct($result) {
    if (QuickAddress::check_soap($result) != NULL) {
      parent::__construct($result->qAddress);
    }
  }
}