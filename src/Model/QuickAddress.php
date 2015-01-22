<?php

namespace Actualys\QasAddressValidationApi\Model;

use Actualys\QasAddressValidationApi\Constant\QasConstant;
use Actualys\QasAddressValidationApi\Model\QAQueryHeader;

/**
 * Class QuickAddress
 */
class QuickAddress {
  public $engineType;
  public $configFile = "";
  public $configSection = "";
  public $engineIntensity = "";
  public $threshold = 0;
  public $timeout = -1;
  public $flatten = FALSE;
  public $soap = NULL;
  public $username = NULL;
  public $password = NULL;
  public $namespace = NULL;

  /**
   * @param $endpointURL
   * @param $username
   * @param $password
   * @param string $namespace
   * @param string $engineType
   * @param array $options
   */
  public function __construct(
    $endpointURL,
    $username,
    $password,
    $namespace = 'http://www.qas.com/OnDemand-2011-03',
    $engineType = 'Singleline',
    $options = array(
      'trace'              => 1,
      'exceptions'         => 1,
      'soap_version'       => SOAP_1_2,
      'connection_timeout' => 20,
      'classmap'           => array(
        'QAAuthentication' => 'QAAuthentication',
        'QAQueryHeader'    => 'QAQueryHeader'
      )
    )
  ) {

    try {
      $this->engineType = $engineType;
      $this->namespace   = $namespace;


      if (!empty(QasConstant::CONTROL_PROXY_NAME)) {
        $this->soap = new \SoapClient(
          $endpointURL,
          array_merge(
            $options,
            array(
              'proxy_host'     => NULL,
              'proxy_port'     => NULL,
              'proxy_login'    => NULL,
              'proxy_password' => NULL
            )
          )
        );
      }
      else {
        $this->soap = new \SoapClient($endpointURL, $options);
      }

      $this->username = $username;
      $this->password = $password;
      if (is_soap_fault($this->soap)) {
        $this->soap = NULL;
      }

    } catch (\Exception $e) {

      $log = 'Empty soap error message';
      if ($this->soap instanceof \SoapClient) {
        $log = 'LAST REQUEST:  ' . $this->soap->__getLastRequest(
          ) . ' LAST RESPONSE: ' . $this->soap->__getLastResponse();
      }

      $message = 'LOG: ' . $log . ' Exception message: ' . $e->getMessage();
      //     file_put_contents(DRUPAL_ROOT . '/sites/default/log/ws_qas_error.log', $message, FILE_APPEND);

      throw new \Exception($message);

    }
  }

  /**
   *
   */
  private function build_auth_header() {
    $b = new QAQueryHeader($this->username, $this->password);

    $authheader = new \SoapHeader($this->namespace, 'QAQueryHeader', $b);

    if ($this->soap instanceof \SoapClient) {
      $this->soap->__setSoapHeaders(array($authheader));
    }
  }


  /**
   * @param $soapResult
   * @return mixed
   * @throws Exception
   */
  public static function check_soap($soapResult) {
    if (is_soap_fault($soapResult)) {
      $err = "QAS SOAP Fault - " . "Code: {" . $soapResult->faultcode . "}, " . "Description: {"
        . $soapResult->faultstring . "}";

      error_log($err, 0);

      $soapResult = NULL;
      throw new \Exception($err);
    }

    return ($soapResult);
  }

  /**
   * @return null
   */
  public function getSoapFault() {
    return (isset($this->soap->__soap_fault) ? $this->soap->__soap_fault->faultstring : NULL);
  }

  /**
   * @param $sFault
   * @return string
   */
  public function getFaultString($sFault) {
    if ((!is_string($sFault) || $sFault == "") && ($this->getSoapFault(
        ) != NULL)
    ) {
      return ("[" . $this->getSoapFault() . "]");
    }
    else {
      return ($sFault);
    }
  }



  /**
   * @return array|null
   * @throws Exception
   */
  public function getAllDataSets() {
    $this->build_auth_header();

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }

    $result = $this->check_soap($this->soap->DoGetData());

    if ($result != NULL) {
      $result = $result->DataSet;

      if (is_array($result)) {
        return ($result);
      }
      else {
        return (array($result));
      }
    }
    else {
      return (NULL);
    }
  }

  /**
   * @param $sID
   * @return array|null
   * @throws Exception
   */
  public function getAllDataMapDetail($sID) {
    $this->build_auth_header();

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $result = $this->check_soap(
      $this->soap->DoGetDataMapDetail(array("DataMap" => $sID))
    );

    if ($result != NULL) {
      $result = $result->LicensedSet;

      if (is_array($result)) {
        return ($result);
      }
      else {
        return (array($result));
      }
    }
    else {
      return (NULL);
    }
  }


  /**
   * @param $dataSetID
   * @return array
   * @throws Exception
   */
  public function getLayouts($dataSetID) {
    $this->build_auth_header();

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }

    $result = $this->check_soap(
      $this->soap->DoGetLayouts(array("Country" => $dataSetID))
    );

    if ($result != NULL) {
      $result = $result->Layout;

      if (is_array($result)) {
        return ($result);
      }
      else {
        return (array($result));
      }
    }
    else {
      return (array());
    }
  }

  /**
   * @param $dataSetID
   * @param $sLayoutName
   * @param string $promptSet
   * @return mixed
   * @throws Exception
   */
  public function canSearch(
    $dataSetID,
    $layoutName,
    $promptSet = "Default"
  ) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }

    $engineOptions = array
    (
      "_"         => $this->engineType,
      "Flatten"   => $this->flatten,
      "PromptSet" => $promptSet
    );

    $args = array
    (
      "Country" => $dataSetID,
      "Engine"  => $engineOptions,
    );

// Set flatten if not default
    if ($this->flatten != NULL) {
      $args["Flatten"] = $this->flatten;
    }

// Set layout (for verification engine) if not default
    if ($layoutName != NULL) {
      $args["Layout"] = $layoutName;
    }
    $this->build_auth_header();

    return ($this->check_soap($this->soap->DoCanSearch($args)));

  }

  /**
   * @param $dataSetID
   * @param $search
   * @param null $promptSet
   * @param null $verifyLayout
   * @param null $requestTag
   * @return SearchResult
   */
  public function search(
    $dataSetID,
    $search,
    $promptSet = NULL,
    $verifyLayout = NULL,
    $requestTag = NULL
  ) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->dataSetID = $dataSetID;

// Concatenate each line of input to a search string delimited by line separator characters
    $searchString = "";
    $first        = TRUE;

    if (isset($search)) {
      if (is_array($search)) {
        foreach ($search AS $itemSearch) {
          if (!$first) {
            $searchString = $searchString . "|"; // todo: separator must be configurable
          }

          $searchString = $searchString . $itemSearch;
          $first        = FALSE;
        }
      }
      else {
        $searchString= $search;
      }
    }


// Set engine type and options - "_" is reserved by PHP SOAP to indicate the
// tag value while the other elements of the array set attribute values
    $engineOptions = array
    (
      "_"       => $this->engineType,
      "Flatten" => $this->flatten
    );

// Set prompt set if not default
    if ($promptSet != NULL) {
      $engineOptions["PromptSet"] = $promptSet;
    }

// Set threshold if not default
    if ($this->threshold != 0) {
      $engineOptions["Threshold"] = $this->threshold;
    }

// Set timeout if not default
    if ($this->timeout != -1) {
      $engineOptions["Timeout"] = $this->timeout;
    }


// Build main search arguments
    $args = array
    (
      "Country" => $this->dataSetID,
      "Search"  => $searchString,
      "Engine"  => $engineOptions
    );

// Are we using a non-default configuration file or section ?
// then setup the appropriate tags
    if ($this->configFile != "" || $this->configSection != "") {
      $config = array();

      if ($this->configFile != "") {
        $config["IniFile"] = $this->configFile;
      }

      if ($this->configSection != "") {
        $config["IniSection"] = $this->configSection;
      }

      $args["QAConfig"] = $config;
    }

// Set layout (for verification engine) if not default
    if ($verifyLayout != NULL) {
      $args["Layout"] = $verifyLayout;
    }

// Set request tag if supplied
    if ($requestTag != NULL) {
      $args["RequestTag"] = $requestTag;
    }

// Perform the web service call and create a SearchResult instance with the result
    $this->build_auth_header();

    return (new SearchResult($this->soap->DoSearch($args)));
  }


  /**
   * @param $dataSetID
   * @param $search
   * @param null $promptSet
   * @param null $verifyLayout
   * @param null $requestTag
   * @return BulkSearchResult
   */
  public function bulkSearch(
    $dataSetID,
    $search,
    $promptSet = NULL,
    $verifyLayout = NULL,
    $requestTag = NULL
  ) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->dataSetID = $dataSetID;

// Concatenate each line of input to a search string delimited by line separator characters
    $searchString = "";
    $bFirst        = TRUE;

// Set engine type and options - "_" is reserved by PHP SOAP to indicate the
// tag value while the other elements of the array set attribute values
    $engineOptions = array
    (
      "_"       => $this->engineType,
      "Flatten" => $this->flatten
    );

// Set prompt set if not default
    if ($promptSet != NULL) {
      $engineOptions["PromptSet"] = $promptSet;
    }

// Set threshold if not default
    if ($this->threshold != 0) {
      $engineOptions["Threshold"] = $this->threshold;
    }

// Set timeout if not default
    if ($this->timeout != -1) {
      $engineOptions["Timeout"] = $this->timeout;
    }


// Build main search arguments
    $args = array
    (
      "Country" => $this->dataSetID,
      "Engine"  => $engineOptions
    );

// Are we using a non-default configuration file or section ?
// then setup the appropriate tags
    if ($this->configFile != "" || $this->configSection != "") {
      $config = array();

      if ($this->configFile != "") {
        $config["IniFile"] = $this->configFile;
      }

      if ($this->configSection != "") {
        $config["IniSection"] = $this->configSection;
      }

      $args["QAConfig"] = $config;
    }

    if ($search != "") {
      $searchTerm = array();

      $searchTerm["Search"] = $search;
      $searchTerm["Count"]  = sizeof($search);
      $args["BulkSearchTerm"] = $searchTerm;
    }


// Set layout (for verification engine) if not default
    if ($verifyLayout != NULL) {
      $args["Layout"] = $verifyLayout;
    }

// Set request tag if supplied
    if ($requestTag != NULL) {
      $args["RequestTag"] = $requestTag;
    }

// Perform the web service call and create a SearchResult instance with the result
    $this->build_auth_header();

    return (new BulkSearchResult($this->soap->DoBulkSearch($args)));
  }

  /**
   * @param $dataSetID
   * @param $search
   * @param null $promptSet
   * @param null $requestTag
   * @return Picklist
   */
  public function searchSingleline(
    $dataSetID,
    $search,
    $promptSet = NULL,
    $requestTag = NULL
  ) {
    $engineOld         = $this->engineType;
    $this->engineType = "Singleline";

    $searchResult      = $this->search(
      $dataSetID,
      $search,
      $promptSet,
      NULL,
      $requestTag
    );
    $this->engineType = $engineOld;

    return ($searchResult->picklist);
  }

  /**
   * @param $sMoniker
   * @param $sRefinementText
   * @param null $requestTag
   * @return Picklist
   */
  public function refine($sMoniker, $sRefinementText, $requestTag = NULL) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $args = array
    (
      "Moniker"    => $sMoniker,
      "Refinement" => $sRefinementText
    );

    if ($this->threshold != 0) {
      $args["Threshold"] = $this->threshold;
    }

    if ($this->timeout != -1) {
      $args["Timeout"] = $this->timeout;
    }

// Set request tag if supplied
    if ($requestTag != NULL) {
      $args["RequestTag"] = $requestTag;
    }

    $this->build_auth_header();

    return (new Picklist($this->soap->DoRefine($args)));
  }

  /**
   * @param $sMoniker
   * @param null $requestTag
   * @return Picklist
   */
  public function stepIn($sMoniker, $requestTag = NULL) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
// A stepin simply creates a picklist from the supplied moniker with a null refinement
    $args = array
    (
      "Moniker"    => $sMoniker,
      "Refinement" => ""
    );

// If the threshold or timeout values are not default then specify them
    if ($this->threshold != 0) {
      $args["Threshold"] = $this->threshold;
    }

    if ($this->timeout != -1) {
      $args["Timeout"] = $this->timeout;
    }

// Set request tag if supplied
    if ($requestTag != NULL) {
      $args["RequestTag"] = $requestTag;
    }

    $this->build_auth_header();

    return (new Picklist($this->soap->DoRefine($args)));
  }

  /**
   * @param $dataSetID
   * @param string $promptSet
   * @param string $sEngine
   * @return null|PromptSet
   * @throws Exception
   */
  public function getPromptSet(
    $dataSetID,
    $promptSet = "Default",
    $sEngine = "Singleline"
  ) {
    $this->build_auth_header();

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $ret = $this->check_soap(
      $this->soap->DoGetPromptSet(
        array
        (
          "Country"   => $dataSetID,
          "PromptSet" => $promptSet,
          "Engine"    => $sEngine
        )
      )
    );

    return (new PromptSet($ret));
  }

  /**
   * @param $sLayoutName
   * @param $sMoniker
   * @param null $requestTag
   * @return FormattedAddress
   */
  public function getFormattedAddress(
    $sLayoutName,
    $sMoniker,
    $requestTag = NULL
  ) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $args = array
    (
      "Layout"  => $sLayoutName,
      "Moniker" => $sMoniker
    );

    // Set request tag if supplied
    if ($requestTag != NULL) {
      $args["RequestTag"] = $requestTag;
    }

    $this->build_auth_header();

    $result = $this->soap->DoGetAddress($args);

    return (new FormattedAddress($result));
  }

  /**
   * @param $dataSetID
   * @return array
   * @throws Exception
   */
  public function getAllLayouts($dataSetID) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->build_auth_header();
    $result = $this->check_soap(
      $this->soap->DoGetLayouts(array("Country" => $dataSetID))
    );

    if ($result != NULL) {
      if (is_array($result->Layout)) {
        return ($result->Layout);
      }
      else {
        return (array($result->Layout));
      }
    }
    else {
      return (array());
    }
  }

  /**
   * @param $dataSetID
   * @param $sLayoutName
   * @param null $requestTag
   * @return Examples
   * @throws Exception
   */
  public function getExampleAddresses(
    $dataSetID,
    $sLayoutName,
    $requestTag = NULL
  ) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $args = array
    (
      "Country" => $dataSetID,
      "Layout"  => $sLayoutName
    );

// Set request tag if supplied
    if ($requestTag != NULL) {
      $args["RequestTag"] = $requestTag;
    }

    $this->build_auth_header();

    $result = $this->check_soap($this->soap->DoGetExampleAddresses($args));

    return (new Examples($result));
  }

  /**
   * @return array|null
   * @throws Exception
   */
  public function getLicenceInfo() {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->build_auth_header();
    $result = $this->check_soap($this->soap->DoGetLicenseInfo());

    if ($result != NULL) {
      if (is_array($result->LicensedSet)) {
        return ($result->LicensedSet);
      }
      else {
        return (array($result->LicensedSet));
      }
    }
    else {
      return (NULL);
    }
  }

  /**
   * @return array|null
   * @throws Exception
   */
  public function getSystemInfo() {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->build_auth_header();
    $result = $this->check_soap($this->soap->DoGetSystemInfo());

    if ($result != NULL) {
      if (is_array($result->SystemInfo)) {
        return ($result->SystemInfo);
      }
      else {
        return (array($result->SystemInfo));
      }
    }
    else {
      return (NULL);
    }
  }
}
