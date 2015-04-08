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

include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');
include_once(dirname(__FILE__).'/../classes/Gateway.php');
include_once(dirname(__FILE__).'/../nqgatewayneteven.php');

$uniqkey = Tools::getValue('uniqkey');

if (Tools::getValue('clean', false))
{
	/* Delete tracking config vars. */
	Gateway::deleteConfig('SYNCHRO_PRODUCT_STEP_'.$uniqkey);
	Gateway::deleteConfig('SYNCHRO_PRODUCT_MESSAGE_'.$uniqkey);

	die('1');
}
else
{
	/* Get tracking config vars */
	$step = Gateway::getConfig('SYNCHRO_PRODUCT_STEP_'.$uniqkey);
	$message = Gateway::getConfig('SYNCHRO_PRODUCT_MESSAGE_'.$uniqkey);

	die(Tools::jsonEncode(array('step' => $step, 'message' => $message)));
}