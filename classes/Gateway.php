<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

ini_set('memory_limit', '512M');
set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once(dirname(__FILE__).'/GatewayOrder.php');
include_once(dirname(__FILE__).'/GatewayProduct.php');
include_once(dirname(__FILE__).'/Toolbox.php');

class Gateway
{
	private $log = '';
	private $id_order_state_neteven = 0;
	private $id_employee_neteven = 0;
	private $id_customer_neteven = 0;
	private $id_lang = 0;
	private $shipping_price_local = 0;
	private $shipping_price_international = 0;
	private $shipping_delay;
	private $comment;
	private $default_brand;
	private $id_country_default;
	private $default_passwd = '';
	private $feature_links;
	private $order_state_before = array();
	private $order_state_after = array();
	/* mailing system, if product not found in BDD.*/
	private $mail_list_alert = array();
	private $mail_active = false;
	/* Possible order states for an order.*/
	private $t_list_order_status = array('Canceled', 'Refunded', 'Shipped', 'toConfirmed');
	private $t_list_order_status_traite = array('Shipped', 'toConfirmed', 'toConfirm', 'Confirmed');
	private $t_list_order_status_retraite_order = array('Canceled', 'Refunded');
	private $debug = false;
	private $send_request_to_mail = false;
	/* Separator for attribute groups / features */
	private $separator = '$-# ';
	public static $send_order_state_to_neteven = true;
	public static $send_product_to_neteven = true;
	protected $client = null;
	public static $translations = array();

	public function __construct($client = null)
	{
		if ($client)
			$this->client = $client;
		else
		{
			$this->client = new SoapClient(Gateway::getConfig('NETEVEN_URL'), array('trace' => 1));
			$auth = $this->createAuthentication(Gateway::getConfig('NETEVEN_LOGIN'), Gateway::getConfig('NETEVEN_PASSWORD'));
			$this->client->__setSoapHeaders(new SoapHeader(Gateway::getConfig('NETEVEN_NS'), 'AuthenticationHeader', $auth));
		}

		$connection = $this->testConnection();

		if ($connection != 'Accepted')
			Toolbox::manageError('Connection non acceptée', 'connexion au webservice');

		$this->affectProperties();
		$this->affectTranslations();
	}

	public function affectProperties()
	{
		$context = Context::getContext();

		// Get the configuration
		$this->shipping_delay = Gateway::getConfig('SHIPPING_DELAY');
		$this->comment = Gateway::getConfig('COMMENT');
		$this->default_brand = Gateway::getConfig('DEFAULT_BRAND');
		$this->id_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
		$this->default_passwd = Gateway::getConfig('PASSWORD_DEFAULT');
		$this->id_employee_neteven = (int)Gateway::getConfig('ID_EMPLOYEE_NETEVEN');
		$this->id_customer_neteven = (int)Gateway::getConfig('ID_CUSTOMER_NETEVEN');
		$this->id_order_state_neteven = (int)Gateway::getConfig('ID_ORDER_STATE_NETEVEN');
		$this->shipping_price_local = Gateway::getConfig('SHIPPING_PRICE_LOCAL');
		$this->shipping_price_international = Gateway::getConfig('SHIPPING_PRICE_INTERNATIONAL');
		$this->id_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$this->feature_links = array();

		/* Get order states */
		if (Gateway::getConfig('ORDER_STATE_BEFORE'))
			$this->order_state_before = explode(':', Gateway::getConfig('ORDER_STATE_BEFORE'));

		if (Gateway::getConfig('ORDER_STATE_AFTER'))
			$this->order_state_after = explode(':', Gateway::getConfig('ORDER_STATE_AFTER'));

		$this->mail_list_alert = explode(':', Gateway::getConfig('MAIL_LIST_ALERT'));
		$this->debug = (Gateway::getConfig('DEBUG') == 1) ? true : false;
		$this->send_request_to_mail = (Gateway::getConfig('SEND_REQUEST_BY_EMAIL') == 1) ? true : false;
		$this->mail_active = (Gateway::getConfig('MAIL_ACTIVE') == 1) ? true : false;

		if ($this->debug == true)
		{
			ini_set('display_errors', 1);
			error_reporting(E_ALL);
		}
	}

	public function affectTranslations()
	{
		require_once(dirname(__FILE__).'/../nqgatewayneteven.php');
		$nqgatewayneteven = new NqGatewayNeteven();

		Gateway::$translations = $nqgatewayneteven->getL();
	}

	public static function getL($key)
	{
		if (!isset(Gateway::$translations[$key]))
			return $key;

		return Gateway::$translations[$key];
	}

	/**
	 * Creating authentication
	 *
	 * @param $login
	 * @param $password
	 * @return array
	 */
	private function createAuthentication($login, $password)
	{
		$seed = '*';
		$stamp = date('c', time());
		$signature = base64_encode(md5(implode('/', array($login, $stamp, $seed, $password)), true));

		return array(
			'Method' => '*',
			'Login' => $login,
			'Seed' => $seed,
			'Stamp' => $stamp,
			'Signature' => $signature
		);
	}

	/**
	 * Test of connection
	 *
	 * @return null|string
	 */
	private function testConnection()
	{
		try
		{
			$response = $this->client->TestConnection();
			$message = $response->TestConnectionResult;
		}
		catch (Exception $e)
		{
			Toolbox::manageError($e, 'Test connection');
			$message = null;
		}

		if (!is_null($message))
			return $message;

		return;
	}

	public function checkConnexion()
	{
		return ($this->testConnection() == 'Accepted');
	}

	public function getValue($name)
	{
		if (empty($this->id_order_state_neteven))
			$this->affectProperties();

		return $this->{$name};
	}

	public static function getConfig($name)
	{
		$value = Db::getInstance()->getValue('
		    SELECT `value`
		    FROM `'._DB_PREFIX_.'orders_gateway_configuration`
		    WHERE name = "'.pSQL($name).'"
		');

		return $value ? $value : false;
	}

	public static function updateConfig($name, $value)
	{
		$config_exist = Db::getInstance()->getValue('
		    SELECT COUNT(*)
		    FROM `'._DB_PREFIX_.'orders_gateway_configuration`
		    WHERE `name` = "'.pSQL($name).'"
		');

		if (!$config_exist)
			Db::getInstance()->execute('
			    INSERT INTO `'._DB_PREFIX_.'orders_gateway_configuration`
			    (`name`, `value`)
			    VALUES ("'.pSQL($name).'", "'.pSQL($value).'")
			');
		else
			Db::getInstance()->execute('
			    UPDATE `'._DB_PREFIX_.'orders_gateway_configuration`
			    SET `value` = "'.pSQL($value).'"
			    WHERE `name` = "'.pSQL($name).'"
			');

	}

	public static function deleteConfig($name)
	{
		return Db::getInstance()->execute('DELETE
		    FROM `'._DB_PREFIX_.'orders_gateway_configuration`
		    WHERE `name` = "'.pSQL($name).'"
		');
	}

	public static function deleteInactiveProduct($ids_product)
	{
		if (!is_array($ids_product))
			$ids_product = array($ids_product);

		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'orders_gateway_inactive_product`
			WHERE id_product IN ('.implode(',', $ids_product).')');
	}

	public static function addInactiveProduct($id_product)
	{
		Db::getInstance()->execute('REPLACE INTO `'._DB_PREFIX_.'orders_gateway_inactive_product` VALUES ('.(int)$id_product.')');
	}

	public function sendDebugMail($emails, $subject, $message, $classic_mail = false)
	{
		if (!$emails)
			return;

		foreach ($emails as $email)
		{
			if (Validate::isEmail($email))
			{
				if (!$classic_mail)
				{
					$id_lang = $this->id_lang ? (int)$this->id_lang : Configuration::get('PS_LANG_DEFAULT');
					$shop_email = Configuration::get('PS_SHOP_EMAIL');
					$shop_name = Configuration::get('PS_SHOP_NAME');
					Mail::Send($id_lang, 'debug', $subject, array(
						'{message}' => $message
					), $email, null, $shop_email, $shop_name, null, null, dirname(__FILE__).'/../mails/');
				}
				else
					mail($email, $subject, $message);

				if ($this->getValue('debug'))
					Toolbox::displayDebugMessage(Gateway::getL('Send email to').' : '.$email);
			}
		}
	}

	/**
	 * Returns a list of specific fields to export the inventory.
	 */
	public static function getFieldsMatchTab()
	{
		/*
		values_group : for select
		type of values :
		- field : data
		- function : script needed to obtain the final value (called a method: param product data) asks another param: callback
		 */
		$t_values = array(
			'identifier' => array(
				'ean13' => array(
					'name' => 'EAN13',
					'type' => 'field',
				),
				'upc' => array(
					'name' => 'UPC',
					'type' => 'field',
				),
				'reference' => array(
					'name' => 'reference',
					'type' => 'field',
				),
			),
			'prices' => array(
				'price_ht' => array(
					'name' => 'Prix de vente HT',
					'type' => 'function',
					'callback' => 'getPriceHT',
					'categoryLinked' => 0,
				),
				'price_ttc' => array(
					'name' => 'Prix de vente TTC',
					'type' => 'function',
					'callback' => 'getPriceTTC',
					'categoryLinked' => 1,
				),
				'price_ttc_without_reduc' => array(
					'name' => 'Prix de vente TTC (Sans réduction)',
					'type' => 'function',
					'callback' => 'getPriceTTCWithoutReduc',
					'categoryLinked' => 1,
				),
				'wholesale_price' => array(
					'name' => 'Prix d\'achat',
					'type' => 'field',
					'categoryLinked' => 0,
				),
			),
			'etat' => array(
				'etat' => array(
					'name' => 'etat',
					'type' => 'function',
					'callback' => 'getEtatNeteven'
				),
			),
			'height' => array(
				'height' => array(
					'name' => 'height',
					'type' => 'field',
				),
			),
			'width' => array(
				'width' => array(
					'name' => 'width',
					'type' => 'field',
				),
			),
			'depth' => array(
				'depth' => array(
					'name' => 'depth',
					'type' => 'field',
				),
			),
		);

		$t_fields = array(
			//-------------------------
			//- Product identifier
			'EAN' => array(
				'values_group' => 'identifier'
			),
			'UPC' => array(
				'values_group' => 'identifier'
			),
			'ISBN' => array(
				'values_group' => 'identifier'
			),
			'ASIN' => array(
				'values_group' => 'identifier'
			),
			//-------------------------
			//- Product prices
			'PriceFixed' => array(
				'values_group' => 'prices'
			),
			'PriceStarting' => array(
				'values_group' => 'prices'
			),
			'PriceReserved' => array(
				'values_group' => 'prices'
			),
			'PriceRetail' => array(
				'values_group' => 'prices'
			),
			'PriceSecondChance' => array(
				'values_group' => 'prices'
			),
			'PriceBestOffer' => array(
				'values_group' => 'prices'
			),
			'PriceAdditional1' => array(
				'values_group' => 'prices'
			),
			'PriceAdditional2' => array(
				'values_group' => 'prices'
			),
			'PriceAdditional3' => array(
				'values_group' => 'prices'
			),
			'PriceAdditional4' => array(
				'values_group' => 'prices'
			),
			'PriceAdditional5' => array(
				'values_group' => 'prices'
			),
			'Etat' => array(
				'values_group' => 'etat'
			),
			'Height' => array(
				'values_group' => 'height'
			),
			'Width' => array(
				'values_group' => 'width'
			),
			'Depth' => array(
				'values_group' => 'depth'
			),
		);

		return array($t_values, $t_fields);
	}

	/**
	 * Retourne la liste des champs supplémentaire pour la synchronisation de l'inventaire.
	 */
	public static function getOthersProductFields()
	{
		$context = Context::getContext();
		$t_fields = array();

		/*
		 * Product table fields
		 */
		$t_fields['product_fields'] = array(
			'on_sale',
			'online_only',
			'minimal_quantity',
			'location',
			'out_of_stock',
			'customizable',
			'uploadable_files',
			'text_fields',
			'active',
			'available_for_order',
			'wholesale_price',
			'date_add',
			'date_upd'
		);
		$t_fields['product_lang_fields'] = array(
			'description_short',
			'meta_description',
			'meta_keywords',
			'meta_title',
			'available_now',
			'available_later',
			'description_without_HTML',
			'description_with_HTML'
		);
		$t_fields['product_attribute_fields'] = array('location', 'minimal_quantity', 'default_on');

		/*
		 * Feature fields
		 */
		$t_fields['feature_fields'] = Feature::getFeatures($context->language->id);

		/*
		 * Attribute fields
		 */
		$t_fields['attribute_fields'] = AttributeGroup::getAttributesGroups($context->language->id);

		return $t_fields;
	}

	public static function getMatchingProductFields()
	{
		// Matcing entre les nom des champs en base et les nom des champs a remplir pour NetEven.
		$match_fields = array(
			'name' => 'title',
			'ean13' => 'ean',
			'upc' => 'UPC',
			'description_with_HTML' => 'description_with_html',
			'description_without_HTML' => 'description_without_html',

		);

		return $match_fields;
	}


	/**
	 * Retourne la liste des langues gérées par Neteven.
	 * Si $with_config, reprend aussi le mapping actuel avec les langue de Presta.
	 */
	public static function getNetevenLanguages($with_config = false)
	{
		$neteven_languages = array(
			'fr' => array(
				'name' => 'fr',
				'code' => 'FR',
			),
			'en' => array(
				'name' => 'en',
				'code' => 'EN',
			),
			'es' => array(
				'name' => 'es',
				'code' => 'ES',
			),
			'de' => array(
				'name' => 'de',
				'code' => 'DE',
			),
			'it' => array(
				'name' => 'it',
				'code' => 'IT',
			),
		);

		if ($with_config)
		{
			foreach ($neteven_languages as &$language)
			{
				$language['config'] = Gateway::getConfig('SYNCHRO_PRODUCT_LANG_'.$language['code']);
				$language['active'] = (int)Gateway::getConfig('SYNCHRO_PRODUCT_LANG_ACTIVE_'.$language['code']);
			}
		}

		return $neteven_languages;
	}

	/**
	 * Retourne la liste des statuts de commande Neteven.
	 *
	 * @param bool $with_config
	 * @return array
	 */
	public static function getNetevenState($with_config = false)
	{
		/*
		 * can_use      : utilisable comme statut à envoyé via le mapping Prestashop => Neteven
		 * accepted     : statut pris en compte lors de l'omport des commande depuis Neteven //TODO: unesed now.
		 * ------
		 * private $t_list_order_status = array('Canceled', 'Refunded', 'Shipped', 'toConfirmed');
		 * private $t_list_order_status_traite = array('Shipped', 'toConfirmed', 'toConfirm', 'Confirmed');
		 * private $t_list_order_status_retraite_order = array('Canceled', 'Refunded');
		 */
		$neteven_state = array(
			'Confirmed' => array(
				'index' => 'Confirmed',
				'name' => 'Confirmed',
				'can_use' => 1,
				'accepted' => 1,
				'in_total_price' => 1,
			),
			'Canceled' => array(
				'index' => 'Canceled',
				'name' => 'Canceled',
				'can_use' => 1,
				'accepted' => 0,
				'in_total_price' => 0,
			),
			'Refunded' => array(
				'index' => 'Refunded',
				'name' => 'Refunded',
				'can_use' => 1,
				'accepted' => 0,
				'in_total_price' => 0,
			),
			'Shipped' => array(
				'index' => 'Shipped',
				'name' => 'Shipped',
				'can_use' => 1,
				'accepted' => 0,
				'in_total_price' => 1,
			),
			'toConfirm' => array(
				'index' => 'toConfirm',
				'name' => 'toConfirm',
				'can_use' => 1,
				'accepted' => 1,
				'in_total_price' => 1,
			),
		);

		if ($with_config)
			foreach ($neteven_state as &$row)
				$row['config'] = (int)Gateway::getConfig('MAPPING_STATE_'.$row['index']);

		return $neteven_state;
	}


	/**
	 * Méthodes de callback.
	 */
	protected function getCurrencyIsoForProduct($id_country)
	{
		$oCountry = new Country((int)$id_country);
		$oCurrency = new Currency((int)$oCountry->id_currency);

		if (!empty($oCurrency->iso_code))
		    return $oCurrency->iso_code;
		else
		    return '';
	}

	protected function getPriceHT($product)
	{
		return Product::getPriceStatic((int)$product['id_product'], false, (int)$product['id_product_attribute'], 2, null, false, true, 1, false, null, null);
	}

	protected function getPriceTTC($product)
	{
		$id_address = (int)Gateway::getAddressByCountry((int)$product['id_country']);

		if (version_compare(_PS_VERSION_, '1.4', '>=') && version_compare(_PS_VERSION_, '1.5', '<'))
		{
			global $cookie;
			$oAddress = new Address($id_address);
			$oCountry = new Country($oAddress->id_country);
			$cookie->id_currency = $oCountry->id_currency;

			return Product::getPriceStatic((int)$product['id_product'], true, (int)$product['id_product_attribute'], 2, null, false, true, 1, false, null, null, $id_address);
		}
		else
		{
			$useless = array();
			$currentContext = Context::getContext();
			$oAddress = new Address($id_address);
			$oCountry = new Country($oAddress->id_country);
			$currentContext->currency = new Currency($oCountry->id_currency);

			return Product::getPriceStatic((int)$product['id_product'], true, (int)$product['id_product_attribute'], 2, null, false, true, 1, false, null, null, $id_address, $useless, true, true, $currentContext);
		}
	}

	protected function getPriceTTCWithoutReduc($product)
	{
		$id_address = Gateway::getAddressByCountry($product['id_country']);

		if (version_compare(_PS_VERSION_, '1.4', '>=') && version_compare(_PS_VERSION_, '1.5', '<'))
		{
			global $cookie;
			$oAddress = new Address($id_address);
			$oCountry = new Country($oAddress->id_country);
			$cookie->id_currency = $oCountry->id_currency;

			return Product::getPriceStatic((int)$product['id_product'], true, (int)$product['id_product_attribute'], 2, null, false, false, 1, false, null, null, $id_address);
		}
		else
		{
			$useless = array();
			$currentContext = Context::getContext();
			$oAddress = new Address($id_address);
			$oCountry = new Country($oAddress->id_country);
			$currentContext->currency = new Currency($oCountry->id_currency);

			return Product::getPriceStatic((int)$product['id_product'], true, (int)$product['id_product_attribute'], 2, null, false, false, 1, false, null, null, $id_address, $useless, true, true, $currentContext);
		}
	}

	protected function getEtatNeteven($product)
	{
		$etat_presta_to_neteven = array(
			'new' => 11,
			'used' => 1,
			'refurbished' => 9,
		);

		$etat = 0;
		if (!empty($product['condition']) && isset($etat_presta_to_neteven[$product['condition']]))
			$etat = $etat_presta_to_neteven[$product['condition']];

		return $etat;
	}

	public static function getAddressByCountry($id_country)
	{
		return (int)Db::getInstance()->getValue('SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias = "adr gen neteven" AND id_country = '.(int)$id_country);
	}


	/**
	 *
	 */
	public static function setStepAjaxCron($step, $message, $uniqkey)
	{
		Gateway::updateConfig('SYNCHRO_PRODUCT_STEP_'.$uniqkey, (int)$step);
		Gateway::updateConfig('SYNCHRO_PRODUCT_MESSAGE_'.$uniqkey, $message);
	}

}