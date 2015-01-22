<?php



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// QAS Pro On Demand - PHP Integration code
// (c) QAS Ltd - www.qas.com
//
//   constants.inc
// defines the action and parameter strings used in all scenarios
//
//   Defines the actions and parameters taken by control.php
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Force these example pages to set charset UTF-8 in the HTTP header
//
// Integrators: You will probably have your own way to handle character
// set issues (if required). Be aware that the SOAP service returns
// UTF-8 encoded results. If you are intending to use country data that
// includes diacritic characters you must convert them if necessary to
// the character set in use on your pages (or elect to return the pages
// in UTF-8 as below)

header('Content-Type: text/html; charset=UTF-8');


// Configuration settings:
// the following values should be altered to suit your integration

// The URN of the OnDemand WSDL served by the OnDemand Server
// Because of a limitation in PHP with accessing WSDL documents over https, this should be downloaded
// from https://ws.ondemand.qas.com/ProOnDemand/V2/ProOnDemandService.asmx?WSDL and stored locally
define("CONTROL_WSDL_URN", "wsv3.wsdl");

// Default dataset
define("DEFAULT_DATASET", "FRX");


//if connecting via a proxy, the following lines should be un-commented

//define("CONTROL_PROXY_NAME", "proxy-burton.omnium.o-p");
//define("CONTROL_PROXY_PORT", 8080);
//define("CONTROL_PROXY_LOGIN", "");
//define("CONTROL_PROXY_PASSWORD", "");

// Username and password
define("USERNAME", "0a16da0b-4e8");
define("PASSWORD", "QASMDP2013");

// Field names:
// these constants are used throughout the pages as the names of fields,
// commands, operations and state

// Control commands

// Command field name
define("CONTROL_COMMAND", "Command");

define("CONTROL_HIERINIT", "HierInit");
define("CONTROL_HIERADDRESS", "HierAddress");
define("CONTROL_HIERSEARCH", "HierSearch");

define("CONTROL_FLATINIT", "FlatInit");
define("CONTROL_FLATPROMPTENTRY", "FlatPromptEntry");
define("CONTROL_FLATSEARCH", "FlatSearch");
define("CONTROL_FINALADDRESS", "FinalAddress");
define("CONTROL_FLATREFINEADDRESS", "FlatRefineAddress");
define("CONTROL_FLATFORMATADDRESS", "FlatFormatAddress");

define("CONTROL_VERIFY", "Verify");
define("CONTROL_VERIFYINIT", "VerifyInit");
define("CONTROL_VERIFYFORMAT", "VerifyFormat");
define("CONTROL_VERIFYREFINE", "VerifyRefine");

define("CONTROL_RAPIDINIT", "RapidInit");
define("CONTROL_RAPIDSEARCH", "RapidSearch");
define("CONTROL_RAPIDADDRESS", "RapidAddress");

define("CONTROL_KEYINIT", "KeyInit");
define("CONTROL_KEYPROMPTENTRY", "KeyPromptEntry");
define("CONTROL_KEYSEARCH", "KeySearch");
define("CONTROL_KEYFORMATADDRESS", "KeyFormatAddress");

// Field names

define("DATA_ID", "DataId");
define("COUNTRY_NAME", "CountryName");
define("USER_INPUT", "UserInput");
define("REFINE_INPUT", "RefineInput");
define("MONIKER", "Moniker");
define("ADDRESS", "Address");
define("SEARCH_ENGINE", "SearchEngine");
define("ERROR_INFO", "ErrorInfo");
define("IS_DYNAMIC", "Dynamic");
define("IS_REFINE", "IsRefine");
define("PICK_TOTAL", "PickTotal");
define("DPVSTATUS", "DpvStatus");
define("REQUEST_TAG_INPUT", "RequestTagInput");

define("WARN_DPVCONFIRMED", "DPVConfirmed");
define("WARN_DPVNOTCONFIRMED", "DPVNotConfirmed");
define("WARN_DPVLOCKED", "DPVLocked");
define("WARN_DPVCONFIRMEDMISSINGSEC", "DPVConfirmedMissingSec");
define("WARN_DPVSEEDHIT", "DPVSeedHit");


// Field names: Flattened

define("PICKLIST_MONIKER", "PicklistMoniker");
define("REFINE_MONIKER", "RefineMoniker");
define("PROMPT_SET", "PromptSet");

// Field names: Verification

define("ORIGINAL_INPUT", "OriginalInput");
define("ADDRESS_INFO", "AddressInfo");

// Field names: Hierarchical

define("STEPIN_WARNING", "StepWarn");
define("PARTIAL", "Partial");
define("POSTCODE", "Postcode");
define("SCORE", "Score");

// Field names: Rapid

define("PICKTEXT", "PickText");
define("CALLBACK_FUNCTION", "Callback");

// Routes

define("ROUTE", "Route");
define("ROUTE_UNSUPPORTED_COUNTRY", "UnsupportedCountry");
define("ROUTE_NORMAL", "Normal");
define("ROUTE_BACK", "Back");
define("ROUTE_RECREATE", "Recreate");
define("ROUTE_FAILED", "Failed");
define("ROUTE_PRODOWN", "ProDown");
define("ROUTE_TIMEOUT", "Timeout");
define("ROUTE_TOOMANYMATCHES", "TooManyMatches");
define("ROUTE_NOMATCHES", "NoMatches");
define("ROUTE_PRECHECKFAILED", "PreSearchFailed");
define("ROUTE_INIT", "Init");
define("ROUTE_UPDATE", "Update");
define("ROUTE_PHANTOM", "Phantom");
define("ROUTE_ALREADYVERIFIED", "Already Verified");

// Hierarchical operations

define("OP_FORMAT", "Format");
define("OP_FORCE_FORMAT", "ForceFormat");
define("OP_STEP_IN", "StepIn");
define("OP_HALT", "Halt");
define("OP_HALT_RANGE", "HaltRange");
define("OP_HALT_INCOMPLETE", "HaltIncomplete");
define("OP_NONE", "None");

define("MONIKER_HISTORY", "MonikerHistory");
define("POSTCODE_HISTORY", "PostcodeHistory");
define("PARTIAL_HISTORY", "PartialHistory");
define("SCORE_HISTORY", "ScoreHistory");
define("PICKTEXT_HISTORY", "PicktextHistory");
define("REFINE_HISTORY", "RefineHistory");

// Step-in warnings

define("WARN_CROSSBORDER", "CrossBorder");
define("WARN_FORCEACCEPT", "ForceAccept");
define("WARN_INFO", "Info");
define("WARN_OVERFLOW", "Overflow");
define("WARN_POSTCODERECODE", "Recoded");
define("WARN_STEPPEDPASTCLOSE", "StepPastClose");
define("WARN_TRUNCATE", "Truncate");

// Picklist item type

define("TYPE_STANDARD", "TypeStandard");
define("TYPE_INFO", "TypeInfo");
define("TYPE_INFO_WARN", "TypeInfoWarn");
define("TYPE_ALIAS", "TypeAlias");
define("TYPE_POBOX", "TypePOBox");
define("TYPE_NAME", "TypeName");
define("TYPE_NAME_ALIAS", "TypeNameAlias");

define("ADDRESSLINE_DATAPLUS", "DataPlus");

define("CONTROL_LAYOUT", "QADefault");
