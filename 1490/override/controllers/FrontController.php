<?php
	/**
	  *
	  * NOTICE OF LICENSE
	  *
	  * Please visit http://www.devstore.in.th/terms-and-conditions-of-use/ for licensing information.
	  *
	  * DISCLAIMER
	  *
	  * Do not edit or add to this file if you wish to upgrade easyfixthurl to newer
	  * versions in the future. If you wish to customize easyfixthurl for your
	  * needs please refer to http://www.devstore.in.th for more information.
	  *
	  * Module EASY FIX THAI Friendly URL for PrestaShop 1.4.9.0
	  *
	  * @author Nokaek Development / devstore.in.th <nokaek@hotmail.com>
	  * @copyright Nokaek Development / nokaek.com
	  * @version 0.11
	  *
	  */

	class FrontController extends FrontControllerCore
	{
		protected function canonicalRedirection()
		{
			global $link, $cookie;

			if (Configuration::get('PS_CANONICAL_REDIRECT') && strtoupper($_SERVER['REQUEST_METHOD']) == 'GET')
			{
				// Automatically redirect to the canonical URL if needed
				if (isset($this->php_self) && !empty($this->php_self))
				{
					// $_SERVER['HTTP_HOST'] must be replaced by the real canonical domain
					$canonicalURL = $link->getPageLink($this->php_self, $this->ssl, $cookie->id_lang);
					if (!Tools::getValue('ajax') && !preg_match('/^'.Tools::pRegexp($canonicalURL, '/').'([&?].*)?$/', (($this->ssl && _PS_SSL_ENABLED_) ? 'https://' : 'http://').urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])))
					{
						if ($_SERVER['REQUEST_URI'] == __PS_BASE_URI__)
						{
							header('HTTP/1.0 303 See Other');
							header('Cache-Control: no-cache');
						}
						else
						{
							header('HTTP/1.0 301 Moved Permanently');
							header('Cache-Control: no-cache');
						}
						
						$params = '';
						$excludedKey = array('isolang', 'id_lang');
						foreach ($_GET as $key => $value)
							if (!in_array($key, $excludedKey))
								$params .= ($params == '' ? '?' : '&').$key.'='.$value;
						Module::hookExec('frontCanonicalRedirect');
						if (defined('_PS_MODE_DEV_') && _PS_MODE_DEV_ && $_SERVER['REQUEST_URI'] != __PS_BASE_URI__)
							die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$canonicalURL.$params.'">'.$canonicalURL.$params.'</a>');
						Tools::redirectLink($canonicalURL.$params);
					}
				}
			}
		}
	}
?>