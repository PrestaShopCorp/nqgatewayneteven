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

if (Tools::getValue('token') != Tools::encrypt(Configuration::get('PS_SHOP_NAME')))
	die(Tools::displayError('Access denied'));
?>
<html>
<head>
	<style type="text/css">
		table {
			width: 100%;
		}

		table tr td {
			border: solid 1px gray;
			padding-left: 15px;
		}
	</style>
</head>
<body>
<script type="text/javascript" src="<?php echo __PS_BASE_URI__; ?>modules/nqgatewayneteven/views/js/jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
	var active = <?php echo (int)Tools::getValue('active', 0); ?>;
	var f = <?php echo (int)Tools::getValue('f', 0); ?>;
	var token = "<?php echo Tools::getValue('token'); ?>";

	var step = 0;
	var checkLoop;
	var uniqkey = new Date().getTime();

	function start() {
		$("#centerBlock").append('<tr><td>Début de l’exécution, merci de patientez...</td></tr>');
		if (!active) {
			$("#centerBlock").append('<tr><td style="background: #ff7271">Attention le flux est en mode affichage, aucune donnée ne sera modifié.</td></tr>');
		}
		$("#centerBlock").append('<tr><td style="background: #f3db2e">Attention : ne pas fermer cette fenêtre pendant l’exécution</td></tr>');
		$.ajax({
			type: "POST",
			url: "<?php echo __PS_BASE_URI__; ?>modules/nqgatewayneteven/script/update-stock.php",
			data: {token: token, uniqkey: uniqkey, active: active, f: f}
		})
			.done(function (msg) {
				window.clearInterval(checkLoop);
				check(true);
			});
		checkLoop = setInterval("check(false)", 500);
	}

	function check(clean) {
		if (typeof clean == 'undefined') {
			clean = false;
		}

		$.ajax({
			type: "POST",
			url: "<?php echo __PS_BASE_URI__; ?>modules/nqgatewayneteven/ajax/check.php",
			data: {token: token, uniqkey: uniqkey},
			dataType: 'json'
		})
			.done(function (jsonData) {

				if (step >= jsonData.step) {
					$("#step_" + jsonData.step).html(jsonData.message);
				}
				else /*if(step < jsonData.step)*/ {
					step = jsonData.step;
					$("#centerBlock").append('<tr><td style="background: #f3db2e" id="step_' + step + '" style="border: solid 1px gray;">' + jsonData.message + '</td></tr>')
				}

				if (clean) {
					$("#centerBlock").append('<tr><td style="background: #adffab">Exécution terminée</td></tr>');
					cleaner();
				}
			});
	}

	function cleaner() {
		$.ajax({
			type: "POST",
			url: "<?php echo __PS_BASE_URI__; ?>modules/nqgatewayneteven/ajax/check.php",
			data: {token: token, uniqkey: uniqkey, clean: 1},
			dataType: 'json'
		})
			.done(function (jsonData) {
			});
	}

	$(document).ready(function () {

		start();

	});


</script>

<table id="centerBlock">
	<tr>
		<td style="font-weight: bold; background: #2fd1ff">Update des stocks</td>
	</tr>
</table>
</body>