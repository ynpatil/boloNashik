<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: Localization.php,v 1.24 2006/08/31 01:09:51 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/


class Localization {
	var $availableCharsets = array(
		'BIG-5'			=> 'BIG-5 (Taiwan and Hong Kong)',
		/*'CP866'			=> 'CP866', // ms-dos Cyrillic */
		/*'CP949'			=> 'CP949 (Microsoft Korean)', */
		'CP1251'		=> 'CP1251 (MS Cyrillic)',
		'CP1252'		=> 'CP1252 (MS Western European & US)',
		'EUC-CN'		=> 'EUC-CN (Simplified Chinese GB2312)',
		'EUC-JP'		=> 'EUC-JP (Unix Japanese)',
		'EUC-KR'		=> 'EUC-KR (Korean)',
		'EUC-TW'		=> 'EUC-TW (Taiwanese)',
		'ISO-2022-JP'	=> 'ISO-2022-JP (Japanese)',
		'ISO-2022-KR'	=> 'ISO-2022-KR (Korean)',
		'ISO-8859-1'	=> 'ISO-8859-1 (Western European and US)',
		'ISO-8859-2'	=> 'ISO-8859-2 (Central and Eastern European)',
		'ISO-8859-3'	=> 'ISO-8859-3 (Latin 3)',
		'ISO-8859-4'	=> 'ISO-8859-4 (Latin 4)',
		'ISO-8859-5'	=> 'ISO-8859-5 (Cyrillic)',
		'ISO-8859-6'	=> 'ISO-8859-6 (Arabic)',
		'ISO-8859-7'	=> 'ISO-8859-7 (Greek)',
		'ISO-8859-8'	=> 'ISO-8859-8 (Hebrew)',
		'ISO-8859-9'	=> 'ISO-8859-9 (Latin 5)',
		'ISO-8859-10'	=> 'ISO-8859-10 (Latin 6)',
		'ISO-8859-13'	=> 'ISO-8859-13 (Latin 7)',
		'ISO-8859-14'	=> 'ISO-8859-14 (Latin 8)',
		'ISO-8859-15'	=> 'ISO-8859-15 (Latin 9)',
		'KOI8-R'		=> 'KOI8-R (Cyrillic Russian)',
		'KOI8-U'		=> 'KOI8-U (Cyrillic Ukranian)',
		'SJIS'			=> 'SJIS (MS Japanese)',
		'UTF-8'			=> 'UTF-8',
		);
	var $localeNameFormat;
	var $localeNameFormatDefault;
	var $default_export_charset = 'CP1252'; // not camel hump to match sugar_config's
	var $default_email_charset = 'ISO-8859-1';
	var $currencies = array(); // array loaded with current currencies

	
	/**
	 * sole constructor
	 */
	function Localization() {
		global $sugar_config;
		$this->localeNameFormatDefault = empty($sugar_config['locale_name_format_default']) ? 's f l' : $sugar_config['default_name_format'];
		$this->loadCurrencies();
	}
	
	/**
	 * returns an array of Sugar Config defaults that are determined by locale settings
	 * @return array
	 */
	function getLocaleConfigDefaults() {
		$coreDefaults = array(
			'currency'								=> '',
			'datef'									=> 'Y-m-d',
			'timef'									=> 'H:i',
			'default_currency_significant_digits'	=> 2,
			'default_currency_symbol'				=> '$',
			'default_export_charset'				=> $this->default_export_charset,
			'default_locale_name_format'			=> 's f l',
			'default_number_grouping_seperator'		=> ',',
			'default_decimal_seperator'				=> '.',
			'export_delimiter'						=> ',',
			'default_email_charset'					=> $this->default_email_charset,
		);

		return $coreDefaults;
	}
	
	/**
	 * abstraction of precedence
	 * @param string prefName Name of preference to retrieve based on overrides
	 * @return string pref Most significant preference
	 */
	function getPrecedentPreference($prefName) {
		global $current_user;
		global $sugar_config;
		
		$userPref = '';
		$coreDefaults = $this->getLocaleConfigDefaults();
		$pref = $coreDefaults[$prefName]; // defaults, even before config.php
		
		if(!empty($current_user)) {
			$userPref = $current_user->getPreference($prefName);
		}
		if(isset($this->$prefName)) {
			$pref = $this->$prefName;
		}
		
		$pref = empty($sugar_config[$prefName]) ? $pref : $sugar_config[$prefName];
		$pref = (empty($userPref) && $userPref !== '0') ? $pref : $userPref;

		return $pref;
	}
	
	///////////////////////////////////////////////////////////////////////////
	////	CURRENCY HANDLING
	/**
	 * wrapper for whatever currency system we implement
	 */
	function loadCurrencies() {
		// doing it dirty here
		global $db;
		global $sugar_config;
		
		if(empty($db)) {
			return array();
		}
		
		// load default from config.php
		$this->currencies['default'] = array(
			'name'		=> $sugar_config['default_currency_name'],
			'symbol'	=> $sugar_config['default_currency_symbol'],
			'conversion_rate' => 1
			);
		
		$q = "SELECT id, name, symbol, conversion_rate FROM currencies WHERE status = 'Active' and deleted = 0";
		$r = $db->query($q);
		
		while($a = $db->fetchByAssoc($r)) {
			$load = array();
			$load['name'] = $a['name'];
			$load['symbol'] = $a['symbol'];
			$load['conversion_rate'] = $a['conversion_rate'];
			
			$this->currencies[$a['id']] = $load;
		}
	}
	
	/**
	 * getter for currencies array
	 * @return array $this->currencies returns array( id => array(name => X, etc
	 */
	function getCurrencies() {
		return $this->currencies;	
	}
	
	/**
	 * retrieves default OOTB currencies for sugar_config and installer.
	 * @return array ret Array of default currencies keyed by ISO4217 code
	 */
	function getDefaultCurrencies() {
		$ret = array(
			'AUD' => array(	'name'		=> 'Austrailian Dollars',
							'iso4217'	=> 'AUD',
							'symbol'	=> '$'),
			'BRL' => array(	'name'		=> 'Brazilian Reais',
							'iso4217'	=> 'BRL',
							'symbol'	=> 'R$'),
			'GBP' => array(	'name'		=> 'British Pounds',
							'iso4217'	=> 'GBP',
							'symbol'	=> '£'),
			'CAD' => array(	'name'		=> 'Candian Dollars',
							'iso4217'	=> 'CAD',
							'symbol'	=> '$'),
			'CNY' => array(	'name'		=> 'Chinese Yuan',
							'iso4217'	=> 'CNY',
							'symbol'	=> '元'),
			'EUR' => array(	'name'		=> 'Euro',
							'iso4217'	=> 'EUR',
							'symbol'	=> '€'),
			'HKD' => array(	'name'		=> 'Hong Kong Dollars',
							'iso4217'	=> 'HKD',
							'symbol'	=> '$'),
			'INR' => array(	'name'		=> 'Indian Rupees',
							'iso4217'	=> 'INR',
							'symbol'	=> '₨'),
			'KRW' => array(	'name'		=> 'Korean Won',
							'iso4217'	=> 'KRW',
							'symbol'	=> '₩'),
			'YEN' => array(	'name'		=> 'Japanese Yen',
							'iso4217'	=> 'JPY',
							'symbol'	=> '¥'),
			'MXM' => array(	'name'		=> 'Mexican Pesos',
							'iso4217'	=> 'MXM',
							'symbol'	=> '$'),
			'SGD' => array(	'name'		=> 'Singaporean Dollars',
							'iso4217'	=> 'SGD',
							'symbol'	=> '$'),
			'CHF' => array(	'name'		=> 'Swiss Franc',
							'iso4217'	=> 'CHF',
							'symbol'	=> 'SFr.'),
			'THB' => array(	'name'		=> 'Thai Baht',
							'iso4217'	=> 'THB',
							'symbol'	=> '฿'),
			'USD' => array(	'name'		=> 'US Dollars',
							'iso4217'	=> 'USD',
							'symbol'	=> '$'),
		);
		
		return $ret;
	}
	////	END CURRENCY HANDLING
	///////////////////////////////////////////////////////////////////////////
	
	
	///////////////////////////////////////////////////////////////////////////
	////	CHARSET TRANSLATION
	/**
	 * returns a mod|app_strings array in the target charset
	 * @param array strings $mod_string, et.al.
	 * @param string charset Target charset
	 * @return array Translated string pack
	 */
	function translateStringPack($strings, $charset) {
		// handle recursive
		foreach($strings as $k => $v) {
			if(is_array($v)) {
				$strings[$k] = $this->translateStringPack($v, $charset);
			} else {
				$strings[$k] = $this->translateCharset($v, 'UTF-8', $charset);
			}
		}
		ksort($strings);
		return $strings;
	}
	
	/**
	 * prepares a bean for export by translating any text fields into the export
	 * character set
	 * @param bean object A SugarBean
	 * @return bean object The bean with translated strings
	 */
    function prepBeanForExport($bean) {
        foreach($bean->field_defs as $k => $field) {
			$bean->$k = $this->translateCharset($bean->$k, 'UTF-8', $this->getExportCharset());
        }
        
        return $bean;
    }
	
	/**
	 * translates a character set from one encoding to another encoding
	 * @param string string the string to be translated
	 * @param string fromCharset the charset the string is currently in
	 * @param string toCharset the charset to translate into (defaults to UTF-8)
	 * @return string the translated string
	 */
	function translateCharset($string, $fromCharset, $toCharset='UTF-8') {
		if(function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($string, $toCharset, $fromCharset);
		} elseif(function_exists('iconv')) { // iconv is flakey
			return iconv($fromCharset, $toCharset, $string);
		} else {
			return $string;
		} // end else clause
	}
	
	/**
	 * translates a character set from one to another, and the into MIME-header friendly format
	 */
	function translateCharsetMIME($string, $fromCharset, $toCharset='UTF-8', $encoding="Q") {
		$mimeString = $this->translateCharset($string, $fromCharset, $toCharset);
		
		return mb_encode_mimeheader($mimeString, $toCharset, $encoding);
	}
	
	function normalizeCharset($charset) {
		$charset = strtolower(preg_replace("/[\-\_]*/", "", $charset));
		return $charset;
	}
	
	/**
	 * returns an array of charsets with keys for available translations; appropriate for get_select_options_with_id()
	 */
	function getCharsetSelect() {
		return $this->availableCharsets;
	}
	
	/**
	 * returns the charset preferred in descending order: User, Sugar Config, DEFAULT
	 * @param string charset to override ALL, pass a valid charset here
	 * @return string charset the chosen character set
	 */
	function getExportCharset($charset='') {
		$charset = $this->getPrecedentPreference('default_export_charset');
		return $charset;	
	}
	////	END CHARSET TRANSLATION
	///////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////////////////////
	////	NUMBER DISPLAY FORMATTING CODE
	function getDecimalSeparator() {
		$dec = $this->getPrecedentPreference('default_decimal_separator');
		return $dec;
	}
	
	function getNumberGroupingSeparator() {
		$sep = $this->getPrecedentPreference('default_number_grouping_seperator');
		return $sep;
	}
	
	/**
	 * returns a number formatted by user preference or system default
	 * @param string number Number to be formatted and returned
	 * @param string currencySymbol Currency symbol if override is necessary
	 * @param bool is_currency Flag to also return the currency symbol
	 * @return string Formatted number
	 */
	function getLocaleFormattedNumber($number, $currencySymbol='', $is_currency=true) {
		$fnum			= $number;
		$majorDigits	= '';
		$minorDigits	= '';
		$dec			= $this->getDecimalSeparator();
		$thou			= $this->getNumberGroupingSeparator();
		$precision		= $this->getPrecision();
		$symbol			= empty($currencySymbol) ? $this->getCurrencySymbol() : $currencySymbol;
		
		$exNum = explode($dec, $number);
		// handle grouping
		if(is_array($exNum) && count($exNum) > 0) {
			if(strlen($exNum) > 3) {
				$offset = strlen($exNum[0]) % 3;
				if($offset > 0) {
					for($i=0; $i<$offset; $i++) {
						$majorDigits .= $exNum[0]{$i};
					}
				}
				
				$tic = 0;
				for($i=$offset; $i<strlen($exNum[0]); $i++) {
					if($tic % 3 == 0 && $i != 0) {
						$majorDigits .= $thou; // add separator
					}
					
					$majorDigits .= $exNum[0]{$i};
					$tic++;
				}
			} else {
				$majorDigits = $exNum[0]; // no formatting needed
			}
			$fnum = $majorDigits;
		}

		// handle decimals
		if($precision > 0) { // we toss the minor digits otherwise
			if(is_array($exNum) && isset($exNum[1])) {
				
			}
		}		
		
		
		if($is_currency) {
			$fnum = $symbol.$fnum;
		}
		return $fnum;
	}

	/**
	 * returns Javascript to format numbers and currency for ***DISPLAY***
	 */
	function getNumberJs() {
		$out = <<<eoq

			var exampleDigits = '123456789.000000';
			
			// round parameter can be negative for decimal, precision has to be postive
			function formatNumber(n, sep, dec, precision) {
				var majorDigits;
				var minorDigits;
				var formattedMajor = '';
				var formattedMinor = '';
				
				var nArray = n.split('.');
				majorDigits = nArray[0];
				if(nArray.length < 2) {
					minorDigits = 0;
				} else {
					minorDigits = nArray[1];
				}

				// handle grouping
				if(sep.length > 0) {
					var strlength = majorDigits.length;
					
					if(strlength > 3) {
						var offset = strlength % 3; // find how many to lead off by
						
						for(j=0; j<offset; j++) {
							formattedMajor += majorDigits[j];
						}
						
						tic=0;
						for(i=offset; i<strlength; i++) {
							if(tic % 3 == 0 && i != 0)
								formattedMajor += sep;
							
							formattedMajor += majorDigits[i];
							tic++;
						}
					}
				} else {
					formattedMajor = majorDigits; // no grouping marker
				}

				// handle decimal precision
				if(precision > 0) {
					for(i=0; i<precision; i++) {
						if(minorDigits[i] != undefined)
							formattedMinor += minorDigits[i];
						else
							formattedMinor += '0';
					}
				} else {
					// we're just returning the major digits, no decimal marker
					dec = ''; // just in case
				}
				
				return formattedMajor + dec + formattedMinor;
			}

			function setSigDigits() {
				var sym = document.getElementById('symbol').value;
				var thou = document.getElementById('default_number_grouping_seperator').value;
				var dec = document.getElementById('default_decimal_seperator').value;
				var precision = document.getElementById('sigDigits').value;
				//umber(n, num_grp_sep, dec_sep, round, precision) 
				var newNumber = sym + formatNumber(exampleDigits, thou, dec, precision, precision);
				document.getElementById('sigDigitsExample').value = newNumber;
			}
eoq;
		return $out;
	}

	////	END NUMBER DISPLAY FORMATTING CODE
	///////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////////////////////
	////	NAME DISPLAY FORMATTING CODE
	/**
	 * get's the Name format macro string, preferring $current_user
	 * @return string format Name Format macro for locale
	 */	
	function getLocaleFormatMacro() {
		$returnFormat = $this->getPrecedentPreference('default_locale_name_format');
		return $returnFormat;
	}
	
	/**
	 * returns formatted name according to $current_user's locale settings
	 * 
	 * @param string firstName
	 * @param string lastName
	 * @param string salutation
	 * @param string format If a particular format is desired, then pass this optional parameter as a simple string.
	 * sfl is "Salutation FirstName LastName", "l, f s" is "LastName[comma][space]FirstName[space]Salutation"
	 * @return string formattedName
	 */
	function getLocaleFormattedName($firstName, $lastName, $salutation='', $format="") {
		global $current_user;

        //check to see if passed in variables are set, if so, then populate array with value,
        //if not, then populate array with blank ''
		$names = array();
		$names['f'] = (empty($firstName)  &&  $firstName  != 0) ? '' : $firstName;
		$names['l'] = (empty($lastName)   &&  $lastName   != 0) ? '' : $lastName;
		$names['s'] = (empty($salutation) &&  $salutation != 0) ? '' : $salutation;

		if(empty($format)) {
			$this->localeNameFormat = $this->getLocaleFormatMacro();
		} else {
			$this->localeNameFormat = $format;
		}
		// parse localeNameFormat
		$formattedName = '';
		for($i=0; $i<strlen($this->localeNameFormat); $i++) {
			$formattedName .= array_key_exists($this->localeNameFormat{$i}, $names) ? $names[$this->localeNameFormat{$i}] : $this->localeNameFormat{$i};
		}
		
		$formattedName = trim($formattedName);
        if (strlen($formattedName)==0) {
            return ' ';
        }

		if(strpos($formattedName,',',strlen($formattedName)-1)) { // remove trailing commas
			$formattedName = substr($formattedName, 0, strlen($formattedName)-1);
		}
		return trim($formattedName);
	}
	
	/**
	 * outputs some simple Javascript to show a preview of Name format in "My Account" and "Admin->Localization"
	 * @param string first First Name, use app_strings default if not specified
	 * @param string last Last Name, use app_strings default if not specified
	 * @param string salutation Saluation, use app_strings default if not specified
	 * @return string some Javascript
	 */
	function getNameJs($first='', $last='', $salutation='') {
		global $app_strings;
		
		$salutation	= !empty($salutation) ? $salutation : $app_strings['LBL_LOCALE_NAME_EXAMPLE_SALUTATION'];
		$first		= !empty($first) ? $first : $app_strings['LBL_LOCALE_NAME_EXAMPLE_FIRST'];
		$last		= !empty($last) ? $last : $app_strings['LBL_LOCALE_NAME_EXAMPLE_LAST'];  

		$ret = "
		function setPreview() {
			
			format = document.getElementById('default_locale_name_format').value;
			//alert('In setPreview() '+format.indexOf(' '));
			formatObj = new Array();
			var index = 0;
			index = format.indexOf(' ');
			var row = 0;
			
			while(index!=-1)
			{
				//alert(' '+format.substring(0,index));
				formatObj[row++] = format.substring(0,index);
				format = format.substring(index+1,format.length);
				index = format.indexOf(' ');
			}
			
			formatObj[row] = format.substring(index+1,format.length);
						
			//alert('Format obj :'+formatObj.length);
			
			field = document.getElementById('nameTarget');
			
			stuff = new Object();
			
			stuff['s'] = '{$salutation} ';
			stuff['f'] = '{$first} ';
			stuff['l'] = '{$last} ';
			
			var name = '';
			for(i=0; i<formatObj.length; i++) {
				if(stuff[formatObj[i]] != undefined) {
					name += stuff[formatObj[i]];
				} else {
					name += formatObj[i];
				}
			}

			//alert(name);
			field.value = name;
		}

		setPreview();";
		
		return $ret;
	}
	////	END NAME DISPLAY FORMATTING CODE
	///////////////////////////////////////////////////////////////////////////
	
} // end class def

?>
