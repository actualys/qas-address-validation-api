<?php

namespace Actualys\QasAddressValidationApi\Model;

/**
 * Class QuickAddress
 */
class QuickAddress {
  public $sEngineType;
  public $sConfigFile = "";
  public $sConfigSection = "";
  public $sEngineIntensity = "";
  public $sDataSetID = "";
  public $iThreshold = 0;
  public $iTimeout = -1;
  public $bFlatten = FALSE;
  public $soap = NULL;
  public $username = NULL;
  public $password = NULL;
  public $namespace = NULL;

  /**
   * @param $sEndpointURL
   * @param $username
   * @param $password
   * @param string $namespace
   * @param string $sEngineType
   * @param array $options
   */
  public function __construct(
    $sEndpointURL,
    $username,
    $password,
    $namespace = 'http://www.qas.com/OnDemand-2011-03',
    $sEngineType = 'Singleline',
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
      $this->sEngineType = $sEngineType;
      $this->namespace   = $namespace;


      if (defined('CONTROL_PROXY_NAME')) {
        $this->soap = new \SoapClient(
          $sEndpointURL,
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
        $this->soap = new \SoapClient($sEndpointURL, $options);
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

      throw new Exception($err);
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
   * @param $sType
   */
  public function setEngineType($sType) {
    $this->sEngineType = $sType;
  }

  /**
   * @param $sIntensity
   */
  public function setEngineIntensity($sIntensity) {
    $this->sEngineIntensity = $sIntensity;
  }

  /**
   * @param $iThreshold
   */
  public function setThreshold($iThreshold) {
    $this->iThreshold = $iThreshold;
  }

  /**
   * @param $iTimeout
   */
  public function setTimeout($iTimeout) {
    $this->iTimeout = $iTimeout;
  }

  /**
   * @param $bFlatten
   */
  public function setFlatten($bFlatten) {
    $this->bFlatten = $bFlatten;
  }

  /**
   * @param $sConfig
   */
  public function setConfigFile($sConfig) {
    $this->sConfigFile = $sConfig;
  }

  /**
   * @param $sSection
   */
  public function setConfigSection($sSection) {
    $this->sConfigSection = $sSection;
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
   * @param $sDataSetID
   * @return array
   * @throws Exception
   */
  public function getLayouts($sDataSetID) {
    $this->build_auth_header();

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }

    $result = $this->check_soap(
      $this->soap->DoGetLayouts(array("Country" => $sDataSetID))
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
   * @param $sDataSetID
   * @param $sLayoutName
   * @param string $sPromptSet
   * @return mixed
   * @throws Exception
   */
  public function canSearch(
    $sDataSetID,
    $sLayoutName,
    $sPromptSet = "Default"
  ) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }

    $aEngineOptions = array
    (
      "_"         => $this->sEngineType,
      "Flatten"   => $this->bFlatten,
      "PromptSet" => $sPromptSet
    );

    $args = array
    (
      "Country" => $sDataSetID,
      "Engine"  => $aEngineOptions,
    );

// Set flatten if not default
    if ($this->bFlatten != NULL) {
      $args["Flatten"] = $this->bFlatten;
    }

// Set layout (for verification engine) if not default
    if ($sLayoutName != NULL) {
      $args["Layout"] = $sLayoutName;
    }
    $this->build_auth_header();

    return ($this->check_soap($this->soap->DoCanSearch($args)));

  }

  /**
   * @param $sDataSetID
   * @param $asSearch
   * @param null $sPromptSet
   * @param null $sVerifyLayout
   * @param null $sRequestTag
   * @return SearchResult
   */
  public function search(
    $sDataSetID,
    $asSearch,
    $sPromptSet = NULL,
    $sVerifyLayout = NULL,
    $sRequestTag = NULL
  ) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->sDataSetID = $sDataSetID;

// Concatenate each line of input to a search string delimited by line separator characters
    $sSearchString = "";
    $bFirst        = TRUE;

    if (isset($asSearch)) {
      if (is_array($asSearch)) {
        foreach ($asSearch AS $sSearch) {
          if (!$bFirst) {
            $sSearchString = $sSearchString . "|"; // todo: separator must be configurable
          }

          $sSearchString = $sSearchString . $sSearch;
          $bFirst        = FALSE;
        }
      }
      else {
        $sSearchString = $asSearch;
      }
    }


// Set engine type and options - "_" is reserved by PHP SOAP to indicate the
// tag value while the other elements of the array set attribute values
    $aEngineOptions = array
    (
      "_"       => $this->sEngineType,
      "Flatten" => $this->bFlatten
    );

// Set prompt set if not default
    if ($sPromptSet != NULL) {
      $aEngineOptions["PromptSet"] = $sPromptSet;
    }

// Set threshold if not default
    if ($this->iThreshold != 0) {
      $aEngineOptions["Threshold"] = $this->iThreshold;
    }

// Set timeout if not default
    if ($this->iTimeout != -1) {
      $aEngineOptions["Timeout"] = $this->iTimeout;
    }


// Build main search arguments
    $args = array
    (
      "Country" => $this->sDataSetID,
      "Search"  => $sSearchString,
      "Engine"  => $aEngineOptions
    );

// Are we using a non-default configuration file or section ?
// then setup the appropriate tags
    if ($this->sConfigFile != "" || $this->sConfigSection != "") {
      $asConfig = array();

      if ($this->sConfigFile != "") {
        $asConfig["IniFile"] = $this->sConfigFile;
      }

      if ($this->sConfigSection != "") {
        $asConfig["IniSection"] = $this->sConfigSection;
      }

      $args["QAConfig"] = $asConfig;
    }

// Set layout (for verification engine) if not default
    if ($sVerifyLayout != NULL) {
      $args["Layout"] = $sVerifyLayout;
    }

// Set request tag if supplied
    if ($sRequestTag != NULL) {
      $args["RequestTag"] = $sRequestTag;
    }

// Perform the web service call and create a SearchResult instance with the result
    $this->build_auth_header();

    return (new SearchResult($this->soap->DoSearch($args)));
  }


  /**
   * @param $sDataSetID
   * @param $asSearch
   * @param null $sPromptSet
   * @param null $sVerifyLayout
   * @param null $sRequestTag
   * @return BulkSearchResult
   */
  public function bulkSearch(
    $sDataSetID,
    $asSearch,
    $sPromptSet = NULL,
    $sVerifyLayout = NULL,
    $sRequestTag = NULL
  ) {

    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->sDataSetID = $sDataSetID;

// Set engine type and options - "_" is reserved by PHP SOAP to indicate the
// tag value while the other elements of the array set attribute values
    $aEngineOptions = array
    (
      "_"       => $this->sEngineType,
      "Flatten" => $this->bFlatten
    );

// Set prompt set if not default
    if ($sPromptSet != NULL) {
      $aEngineOptions["PromptSet"] = $sPromptSet;
    }

// Set threshold if not default
    if ($this->iThreshold != 0) {
      $aEngineOptions["Threshold"] = $this->iThreshold;
    }

// Set timeout if not default
    if ($this->iTimeout != -1) {
      $aEngineOptions["Timeout"] = $this->iTimeout;
    }


// Build main search arguments
    $args = array
    (
      "Country" => $this->sDataSetID,
      "Engine"  => $aEngineOptions
    );

// Are we using a non-default configuration file or section ?
// then setup the appropriate tags
    if ($this->sConfigFile != "" || $this->sConfigSection != "") {
      $asConfig = array();

      if ($this->sConfigFile != "") {
        $asConfig["IniFile"] = $this->sConfigFile;
      }

      if ($this->sConfigSection != "") {
        $asConfig["IniSection"] = $this->sConfigSection;
      }

      $args["QAConfig"] = $asConfig;
    }

    if ($asSearch != "") {
      $asSearchTerm = array();

      $asSearchTerm["Search"] = $asSearch;
      $asSearchTerm["Count"]  = sizeof($asSearch);
      $args["BulkSearchTerm"] = $asSearchTerm;
    }


// Set layout (for verification engine) if not default
    if ($sVerifyLayout != NULL) {
      $args["Layout"] = $sVerifyLayout;
    }

// Set request tag if supplied
    if ($sRequestTag != NULL) {
      $args["RequestTag"] = $sRequestTag;
    }

// Perform the web service call and create a SearchResult instance with the result
    $this->build_auth_header();

    return (new BulkSearchResult($this->soap->DoBulkSearch($args)));
  }

  /**
   * @param $sDataSetID
   * @param $asSearch
   * @param null $sPromptSet
   * @param null $sRequestTag
   * @return Picklist
   */
  public function searchSingleline(
    $sDataSetID,
    $asSearch,
    $sPromptSet = NULL,
    $sRequestTag = NULL
  ) {
    $engineOld         = $this->sEngineType;
    $this->sEngineType = "Singleline";

    $searchResult      = $this->search(
      $sDataSetID,
      $asSearch,
      $sPromptSet,
      NULL,
      $sRequestTag
    );
    $this->sEngineType = $engineOld;

    return ($searchResult->picklist);
  }

  /**
   * @param $sMoniker
   * @param $sRefinementText
   * @param null $sRequestTag
   * @return Picklist
   */
  public function refine($sMoniker, $sRefinementText, $sRequestTag = NULL) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $args = array
    (
      "Moniker"    => $sMoniker,
      "Refinement" => $sRefinementText
    );

    if ($this->iThreshold != 0) {
      $args["Threshold"] = $this->iThreshold;
    }

    if ($this->iTimeout != -1) {
      $args["Timeout"] = $this->iTimeout;
    }

// Set request tag if supplied
    if ($sRequestTag != NULL) {
      $args["RequestTag"] = $sRequestTag;
    }

    $this->build_auth_header();

    return (new Picklist($this->soap->DoRefine($args)));
  }

  /**
   * @param $sMoniker
   * @param null $sRequestTag
   * @return Picklist
   */
  public function stepIn($sMoniker, $sRequestTag = NULL) {

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
    if ($this->iThreshold != 0) {
      $args["Threshold"] = $this->iThreshold;
    }

    if ($this->iTimeout != -1) {
      $args["Timeout"] = $this->iTimeout;
    }

// Set request tag if supplied
    if ($sRequestTag != NULL) {
      $args["RequestTag"] = $sRequestTag;
    }

    $this->build_auth_header();

    return (new Picklist($this->soap->DoRefine($args)));
  }

  /**
   * @param $sDataSetID
   * @param string $sPromptSet
   * @param string $sEngine
   * @return null|PromptSet
   * @throws Exception
   */
  public function getPromptSet(
    $sDataSetID,
    $sPromptSet = "Default",
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
          "Country"   => $sDataSetID,
          "PromptSet" => $sPromptSet,
          "Engine"    => $sEngine
        )
      )
    );

    return (new PromptSet($ret));
  }

  /**
   * @param $sLayoutName
   * @param $sMoniker
   * @param null $sRequestTag
   * @return FormattedAddress
   */
  public function getFormattedAddress(
    $sLayoutName,
    $sMoniker,
    $sRequestTag = NULL
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
    if ($sRequestTag != NULL) {
      $args["RequestTag"] = $sRequestTag;
    }

    $this->build_auth_header();

    $result = $this->soap->DoGetAddress($args);

    return (new FormattedAddress($result));
  }

  /**
   * @param $sDataSetID
   * @return array
   * @throws Exception
   */
  public function getAllLayouts($sDataSetID) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $this->build_auth_header();
    $result = $this->check_soap(
      $this->soap->DoGetLayouts(array("Country" => $sDataSetID))
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
   * @param $sDataSetID
   * @param $sLayoutName
   * @param null $sRequestTag
   * @return Examples
   * @throws Exception
   */
  public function getExampleAddresses(
    $sDataSetID,
    $sLayoutName,
    $sRequestTag = NULL
  ) {
    if (!$this->soap instanceof \SoapClient) {
      return NULL;
    }
    $args = array
    (
      "Country" => $sDataSetID,
      "Layout"  => $sLayoutName
    );

// Set request tag if supplied
    if ($sRequestTag != NULL) {
      $args["RequestTag"] = $sRequestTag;
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
