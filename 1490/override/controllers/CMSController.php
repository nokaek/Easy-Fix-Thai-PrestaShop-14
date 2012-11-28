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

	class CmsController extends CmsControllerCore
	{
		public function canonicalRedirection()
		{
			// Automatically redirect to the canonical URL if the current in is the right one
			// $_SERVER['HTTP_HOST'] must be replaced by the real canonical domain
			if (Configuration::get('PS_CANONICAL_REDIRECT') && strtoupper($_SERVER['REQUEST_METHOD']) == 'GET')
			{
				if (Validate::isLoadedObject($this->cms) AND $canonicalURL = self::$link->getCMSLink($this->cms))
					if (!preg_match('/^'.Tools::pRegexp($canonicalURL, '/').'([&?].*)?$/', Tools::getProtocol().urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])))
					{
						header('HTTP/1.0 301 Moved');
						header('Cache-Control: no-cache');
						if (_PS_MODE_DEV_)
							die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$canonicalURL.'">'.$canonicalURL.'</a>');
						Tools::redirectLink($canonicalURL);
					}
					if (Validate::isLoadedObject($this->cms_category) AND $canonicalURL = self::$link->getCMSCategoryLink($this->cms_category))
					if (!preg_match('/^'.Tools::pRegexp($canonicalURL, '/').'([&?].*)?$/', Tools::getProtocol().urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])))
					{
						header('HTTP/1.0 301 Moved');
						header('Cache-Control: no-cache');
						if (_PS_MODE_DEV_ )
							die('[Debug] This page has moved<br />Please use the following URL instead: <a href="'.$canonicalURL.'">'.$canonicalURL.'</a>');
						Tools::redirectLink($canonicalURL);
					}
			}
		}
	}
?>