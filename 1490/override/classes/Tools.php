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

	class Tools extends ToolsCore
	{
		public static function generateHtaccess($path, $rewrite_settings, $cache_control, $specific = '', $disableMultiviews = false)
		{
			$tab = array('ErrorDocument' => array(), 'RewriteEngine' => array(), 'RewriteRule' => array());
			$multilang = (Language::countActiveLanguages() > 1);

			// ErrorDocument
			$tab['ErrorDocument']['comment'] = '# Catch 404 errors';
			$tab['ErrorDocument']['content'] = '404 '.__PS_BASE_URI__.'404.php';

			// RewriteEngine
			$tab['RewriteEngine']['comment'] = '# URL rewriting module activation';

			// RewriteRules
			$tab['RewriteRule']['comment'] = '# URL rewriting rules';

			// Compatibility with the old image filesystem
			if (Configuration::get('PS_LEGACY_IMAGES'))
			{
				$tab['RewriteRule']['content']['^([a-z0-9]+)\-([a-z0-9]+)(\-.*)/.*\.jpg$'] = _PS_PROD_IMG_.'$1-$2$3.jpg [L]';
				$tab['RewriteRule']['content']['^([0-9]+)\-([0-9]+)/.*\.jpg$'] = _PS_PROD_IMG_.'$1-$2.jpg [L]';
			}

			// Rewriting for product image id < 100 millions
			$tab['RewriteRule']['content']['^([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$1$2.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$1$2$3.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$3/$1$2$3$4.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$3/$4/$1$2$3$4$5.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])([0-9])([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$3/$4/$5/$1$2$3$4$5$6.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$3/$4/$5/$6/$1$2$3$4$5$6$7.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$3/$4/$5/$6/$7/$1$2$3$4$5$6$7$8.jpg [L]';
			$tab['RewriteRule']['content']['^([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])([0-9])(\-.*)?/.*\.jpg$'] = _PS_PROD_IMG_.'$1/$2/$3/$4/$5/$6/$7/$8/$1$2$3$4$5$6$7$8$9.jpg [L]';

			$tab['RewriteRule']['content']['^c/([0-9]+)(\-.*)/.*\.jpg$'] = 'img/c/$1$2.jpg [L]';
		$tab['RewriteRule']['content']['^c/(.+)/.+\.jpg$'] = 'img/c/$1.jpg [L]';
			$tab['RewriteRule']['content']['^c/(.+)/.+\.jpg$'] = 'img/c/$1.jpg [L]';

			if ($multilang)
			{
				$tab['RewriteRule']['content']['^([a-z]{2})/.*/([0-9]+)\-.*\.html'] = 'product.php?id_product=$2&isolang=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/([0-9]+)\-.*\.html'] = 'product.php?id_product=$2&isolang=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/([0-9]+)\-.*(/.*)+'] = 'category.php?id_category=$2&isolang=$1&noredirect=1 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/([0-9]+)\-.*'] = 'category.php?id_category=$2&isolang=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/content/([0-9]+)\-.*'] = 'cms.php?isolang=$1&id_cms=$2 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/content/category/([0-9]+)\-.*'] = 'cms.php?isolang=$1&id_cms_category=$2 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/([0-9]+)__.*'] = 'supplier.php?isolang=$1&id_supplier=$2 [QSA,L]';
				$tab['RewriteRule']['content']['^([a-z]{2})/([0-9]+)_.*'] = 'manufacturer.php?isolang=$1&id_manufacturer=$2 [QSA,L]';
			}

			// PS BASE URI automaticaly prepend the string, do not use PS defines for the image directories
			$tab['RewriteRule']['content']['^([0-9]+)(\-.*)/.*\.jpg$'] = 'img/c/$1$2.jpg [L]';

			$tab['RewriteRule']['content']['^([0-9]+)\-.*\.html'] = 'product.php?id_product=$1 [QSA,L]';
			$tab['RewriteRule']['content']['^.*/([0-9]+)\-.*\.html'] = 'product.php?id_product=$1 [QSA,L]';
			// Notice : the id_category rule has to be after product rules.
			// If not, category with number in their name will result a bug
			$tab['RewriteRule']['content']['^([0-9]+)\-.*(/.*)+'] = 'category.php?id_category=$1&noredirect=1 [QSA,L]';
			$tab['RewriteRule']['content']['^([0-9]+)\-.*'] = 'category.php?id_category=$1 [QSA,L]';
			$tab['RewriteRule']['content']['^([0-9]+)__(.*)'] = 'supplier.php?id_supplier=$1 [QSA,L]';
			$tab['RewriteRule']['content']['^([0-9]+)_(.*)'] = 'manufacturer.php?id_manufacturer=$1 [QSA,L]';
			$tab['RewriteRule']['content']['^content/([0-9]+)\-(.*)'] = 'cms.php?id_cms=$1 [QSA,L]';
			$tab['RewriteRule']['content']['^content/category/([0-9]+)\-(.*)'] = 'cms.php?id_cms_category=$1 [QSA,L]';

			// Compatibility with the old URLs
			if (!Configuration::get('PS_INSTALL_VERSION') OR version_compare(Configuration::get('PS_INSTALL_VERSION'), '1.4.0.7') == -1)
			{
				// This is a nasty copy/paste of the previous links, but with "lang-en" instead of "en"
				// Do not update it when you add something in the one at the top, it's only for the old links
				$tab['RewriteRule']['content']['^lang-([a-z]{2})/(.*)/([0-9]+)\-(.*)\.html'] = 'product.php?id_product=$3&isolang=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^lang-([a-z]{2})/([0-9]+)\-(.*)\.html'] = 'product.php?id_product=$2&isolang=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^lang-([a-z]{2})/([0-9]+)\-(.*)'] = 'category.php?id_category=$2&isolang=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^content/([0-9]+)\-(.*)'] = 'cms.php?id_cms=$1 [QSA,L]';
				$tab['RewriteRule']['content']['^content/category/([0-9]+)\-(.*)'] = 'cms.php?id_cms_category=$1 [QSA,L]';
			}

			Language::loadLanguages();
			$default_meta = Meta::getMetasByIdLang((int)_PS_LANG_DEFAULT_);

			if ($multilang)
				foreach (Language::getLanguages() as $language)
				{
					foreach (Meta::getMetasByIdLang($language['id_lang']) as $key => $meta)
						if (!empty($meta['url_rewrite']) AND Validate::isLinkRewrite($meta['url_rewrite']))
							$tab['RewriteRule']['content']['^'.$language['iso_code'].'/'.$meta['url_rewrite'].'$'] = $meta['page'].'.php?isolang='.$language['iso_code'].' [QSA,L]';
						elseif (array_key_exists($key, $default_meta) && $default_meta[$key]['url_rewrite'] != '')
							$tab['RewriteRule']['content']['^'.$language['iso_code'].'/'.$default_meta[$key]['url_rewrite'].'$'] = $default_meta[$key]['page'].'.php?isolang='.$language['iso_code'].' [QSA,L]';
					$tab['RewriteRule']['content']['^'.$language['iso_code'].'$'] = $language['iso_code'].'/ [QSA,L]';
					$tab['RewriteRule']['content']['^'.$language['iso_code'].'/([^?&]*)$'] = '$1?isolang='.$language['iso_code'].' [QSA,L]';
				}
			else
				foreach ($default_meta as $key => $meta)
					if (!empty($meta['url_rewrite']))
						$tab['RewriteRule']['content']['^'.$meta['url_rewrite'].'$'] = $meta['page'].'.php [QSA,L]';
					elseif (array_key_exists($key, $default_meta) && $default_meta[$key]['url_rewrite'] != '')
						$tab['RewriteRule']['content']['^'.$default_meta[$key]['url_rewrite'].'$'] = $default_meta[$key]['page'].'.php [QSA,L]';

			if (!$writeFd = @fopen($path, 'w'))
				return false;

			// PS Comments
			fwrite($writeFd, "# .htaccess automaticaly generated by PrestaShop e-commerce open-source solution\n");
			fwrite($writeFd, "# WARNING: PLEASE DO NOT MODIFY THIS FILE MANUALLY. IF NECESSARY, ADD YOUR SPECIFIC CONFIGURATION WITH THE HTACCESS GENERATOR IN BACK OFFICE\n");
			fwrite($writeFd, "# http://www.prestashop.com - http://www.prestashop.com/forums\n\n");
			if (!empty($specific))
				fwrite($writeFd, $specific);

			// RewriteEngine
			fwrite($writeFd, "\n<IfModule mod_rewrite.c>\n");

			if ($disableMultiviews)
				fwrite($writeFd, "\n# Disable Multiviews\nOptions -Multiviews\n\n");

			fwrite($writeFd, $tab['RewriteEngine']['comment']."\nRewriteEngine on\n\n");
			fwrite($writeFd, $tab['RewriteRule']['comment']."\n");
			// Webservice
			if (Configuration::get('PS_WEBSERVICE'))
			{
				fwrite($writeFd, 'RewriteRule ^api/?(.*)$ '.__PS_BASE_URI__."webservice/dispatcher.php?url=$1 [QSA,L]\n");
				if (Configuration::get('PS_WEBSERVICE_CGI_HOST'))
					fwrite($writeFd, 'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]'."\n");
			}

			// Classic URL rewriting
			if ($rewrite_settings)
				foreach ($tab['RewriteRule']['content'] as $rule => $url)
					fwrite($writeFd, 'RewriteRule '.$rule.' '.__PS_BASE_URI__.$url."\n");

			fwrite($writeFd, "</IfModule>\n\n");

			// ErrorDocument
			fwrite($writeFd, $tab['ErrorDocument']['comment']."\nErrorDocument ".$tab['ErrorDocument']['content']."\n");

			// Cache control
			if ($cache_control)
			{
				fwrite($writeFd, '
	<IfModule mod_expires.c>
		ExpiresActive On
	ExpiresByType image/gif "access plus 1 month"
	ExpiresByType image/jpeg "access plus 1 month"
	ExpiresByType image/png "access plus 1 month"
	ExpiresByType text/css "access plus 1 week"
	ExpiresByType text/javascript "access plus 1 week"
	ExpiresByType application/javascript "access plus 1 week"
	ExpiresByType application/x-javascript "access plus 1 week"
	ExpiresByType image/x-icon "access plus 1 year"
	</IfModule>

	FileETag INode MTime Size
<IfModule mod_deflate.c>
	<IfModule mod_filter.c>
		AddOutputFilterByType DEFLATE text/html text/css text/plain text/javascript application/javascript application/x-javascript
	</IfModule>
</IfModule>'."\n");
		}
		fclose($writeFd);

			Module::hookExec('afterCreateHtaccess');

			return true;
		}	
	}