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

class Toolbox
{
	private static $log;
	protected static $handle_instance = false;

	public static function manageError($e, $type_error)
	{
		Toolbox::writeLog(true, $type_error.' => '.$e);
	}

	public static function writeLog($is_error = false, $message = '')
	{
		if (!self::$handle_instance)
			self::$handle_instance = @fopen(dirname(__FILE__).'/../logs/logs-'.date('Y-m-d').'.txt', 'a+');

		if (!empty(self::$log) && !$is_error)
		{
			if (self::$handle_instance)
				fwrite(self::$handle_instance, self::$log);

			self::$log = '';
		}

		if ($is_error)
		{
			if (self::$handle_instance)
				fwrite(self::$handle_instance, date('Y-m-d H:i:s').' - '.$message."\n");
		}
	}

	public static function addLogLine($string, $time = true)
	{
		self::$log .= ($time ? date('Y-m-d H:i:s').' - ' : "\t").$string."\n";
	}

	public static function numericFilter($string)
	{
		return preg_replace('/[^0-9]/u', '', $string);
	}

	public static function stringFilter($string)
	{
		return preg_replace('/[^àáâãäåçèéêëìíîïðòóôõöùúûüýÿa-zA-Z- ]/u', ' ', $string);
	}

	public static function stringWithNumericFilter($string)
	{
		return preg_replace('/[^àáâãäåçèéêëìíîïðòóôõöùúûüýÿa-zA-Z0-9- ]/u', ' ', $string);
	}

	public static function existAddress($order_infos, $id_country, $id_customer)
	{
		$addr = Db::getInstance()->getRow('
			SELECT * FROM '._DB_PREFIX_.'address WHERE
			address1 = "'.pSQL($order_infos->Address1).'" AND
			address2 = "'.pSQL($order_infos->Address2).'" AND
			city = "'.pSQL($order_infos->CityName).'" AND
			firstname = "'.pSQL($order_infos->FirstName).'" AND
			lastname = "'.pSQL($order_infos->LastName).'" AND
			postcode = "'.pSQL($order_infos->PostalCode).'" AND
			phone = "'.pSQL(Toolbox::numericFilter($order_infos->Phone)).'" AND
			phone_mobile = "'.pSQL(Toolbox::numericFilter($order_infos->Mobile)).'" AND
			id_country = '.(int)$id_country.' AND
			id_customer = '.(int)$id_customer);

		if ($addr)
			return $addr['id_address'];
		else
			return false;
	}

	public static function removeAccents($str, $charset = 'utf-8')
	{
		$str = htmlentities($str, ENT_NOQUOTES, $charset);

		$str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#u', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#u', '\1', $str);
		$str = preg_replace('#&[^;]+;#u', '', $str);

		return $str;
	}

	public static function displayDebugMessage($message, $error = false)
	{
		echo ($error ? '<span style="color:red;">' : '').$message.($error ? '</span>' : '').'<br />';
	}
}