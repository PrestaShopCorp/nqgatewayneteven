{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
	var moduleDir = "{$module_dir|escape:'htmlall':'UTF-8'}";
	var text_field_name = "{l s='Nom du champs' mod='nqgatewayneteven' js=1}";
	var text_value = "{l s='Valeur' mod='nqgatewayneteven' js=1}";
	var neteven_token = "{$neteven_token|escape:'htmlall':'UTF-8'}";
	var SHIPPING_ZONE_FRANCE = "{$SHIPPING_ZONE_FRANCE|floatval}";
	var SHIPPING_CARRIER_INTERNATIONAL = "{$SHIPPING_CARRIER_INTERNATIONAL|floatval}";
	var SHIPPING_ZONE_INTERNATIONAL = "{$SHIPPING_ZONE_INTERNATIONAL|floatval}";
</script>
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/nqgatewayneteven.js"></script>
<link href="{$module_dir|escape:'htmlall':'UTF-8'}views/css/nqgatewayneteven.css" rel="stylesheet" type="text/css" media="all" />

<fieldset>
	<h2 style="margin-top: 0;"><a href="http://www.neteven.com/" target="_blank"><img src="{$module_path|escape:'htmlall':'UTF-8'}/views/img/logo.gif" style="vertical-align:middle; margin-right:15px;" alt="{$module_display_name|escape:'htmlall':'UTF-8'}" /></a></h2>

	<p class="neteven-description-title">{l s='NetEven, logiciel et services dediés à la gestion des marketplaces européennes' mod='nqgatewayneteven'}</p>
	<p>
	{l s='Synchronisez votre site PrestaShop avec Neteven et pilotez tout le cycle de vente sur les marketplaces de manière simple et intégrée.' mod='nqgatewayneteven'}<br />
	{l s='Distribuez vos produits en Europe sur les plus grands sites e-commerce depuis une interface unique :'  mod='nqgatewayneteven'}  La Redoute - Amazon - eBay – Pixmania – PriceMinister - Cdiscount.com – SFR – RueDuCommerce – Fnac - BrandAlley - OneWorldAvenue - Zalando – Atosho – Play – Hmv - Otto.de - Rakuten.de ...<br />
	{l s='Vos produits seront à la disposition de plus de 20 millions d\'acheteurs français et 50 millions d\'européens !' mod='nqgatewayneteven'}<br/>
		<br />
		<span style="font-size: 1.1em;">{l s='Parmi les fonctionnalités disponibles :' mod='nqgatewayneteven'}</span>
	</p>

	<ul style="list-style: disc; margin-top: -5px; margin-left: 20px; font-weight: ">
		<li>{l s='Inventaire central compatible avec l\'ensemble des marketplaces' mod='nqgatewayneteven'}</li>
		<li>{l s='Publication par code barre' mod='nqgatewayneteven'}</li>
		<li>{l s='Création de fiches produits complètes' mod='nqgatewayneteven'}</li>
		<li>{l s='Automatisation des étapes du cycle de vente' mod='nqgatewayneteven'}</li>
		<li>{l s='Outils d\'animation commerciale' mod='nqgatewayneteven'}</li>
		<li>{l s='Fonctionnalité d\'ajustement des prix sur le concurrence' mod='nqgatewayneteven'}</li>
		<li>{l s='Centralisation des fins de transaction' mod='nqgatewayneteven'}</li>
		<li>{l s='Automatisation de la communication client' mod='nqgatewayneteven'}</li>
		<li>{l s='Module de Reporting avancé' mod='nqgatewayneteven'}</li>
		<li>{l s='Imports / exports personnalisables' mod='nqgatewayneteven'}</li>
		<li>{l s='Editions des factures, bordereaux, étiquettes...' mod='nqgatewayneteven'}</li>
	</ul>

	<p class="neteven-description-title">{l s='Services d\'accompagnement à la distribution' mod='nqgatewayneteven'}</p>
	<p>{l s='Appuyez vous sur les experts marketplaces de l\'équipe Neteven pour vendre plus et mieux ! Toutes les informations sur les services proposés par NetEven sont disponibles' mod='nqgatewayneteven'} <a href="http://www.neteven.com/offres-logiciel-service.html" target="_blank" style="text-decoration: underline;">{l s='ici' mod='nqgatewayneteven'}</a>.</p>

	<p class="neteven-description-title">{l s='Configuration du module' mod='nqgatewayneteven'}</p>
	<p>{l s='Créez votre compte client' mod='nqgatewayneteven'} <a href="http://www.neteven.com/neteven-inscription.html" target="_blank" style="text-decoration: underline;">{l s='ici' mod='nqgatewayneteven'}</a> {l s='puis paramétrez le module en remplissant les formulaires ci-dessous.' mod='nqgatewayneteven'}</p>

	<p class="neteven-description-title">{l s='Documentation' mod='nqgatewayneteven'}</p>
	<a href="/modules/nqgatewayneteven/Neteven_module_Prestashop.pdf" target="_blank">{l s='Voir la documentation' mod='nqgatewayneteven'}</a>
</fieldset>

{assign "default_select_value" "--"}

{***********************************************************************}
{*  Connexion *}
{***********************************************************************}
<form action="" method="post">
<fieldset style="margin-top: 15px;" >
	<legend><img src="../img/admin/cog.gif" alt="{l s='Paramètres de connexion' mod='nqgatewayneteven'}" class="middle" />{l s='Paramètres de connexion' mod='nqgatewayneteven'}</legend>
	<div class="warning">{l s='Attention, ces paramètres sont indispensable au fonctionnement du module.' mod='nqgatewayneteven'}</div>
	<label>{l s='Identifiant NetEven' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" id="NETEVEN_LOGIN" name="NETEVEN_LOGIN" value="{$NETEVEN_LOGIN|escape:'htmlall':'UTF-8'}" />
	</div>
	<label>{l s='Mot de passe NetEven' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="password" id="NETEVEN_PASSWORD" name="NETEVEN_PASSWORD" value="{$NETEVEN_PASSWORD|escape:'htmlall':'UTF-8'}" />
	</div>
	<br class="clear" />
	<div style="display:none;" id="conf-connexion-success" class="conf-connexion conf confirm">
		{l s='Vos identifiants ont été correctement validés.' mod='nqgatewayneteven'}
	</div>
	<div style="display:none;" id="conf-connexion-error" class="conf-connexion error">
		{l s='Vos identifiants n’ont pas été reconnu. Veuillez-vous rapprocher de votre responsable de compte Neteven.' mod='nqgatewayneteven'}
	</div>
	<br class="clear" />
	<div style="text-align: center;">
		<input type="button" id="submitTestConnexion" value="{l s='Tester les paramètres de connexion' mod='nqgatewayneteven'}" class="button" />
		<input type="submit" name="submitNetEvenConnexion" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" />
	</div>
</fieldset>

{***********************************************************************}
{*  Synchronisation des produits *}
{***********************************************************************}
<fieldset style="margin-top: 15px;" >
	<legend><img src="../img/admin/cog.gif" alt="{l s='Synchronisation de l\'inventaire' mod='nqgatewayneteven'}" class="middle" />{l s='Synchronisation de l\'inventaire' mod='nqgatewayneteven'}</legend>
	<label>{l s='Actif' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="radio" name="SYNCHRONISATION_PRODUCT" id="SYNCHRONISATION_PRODUCT_on" value="1"{if $SYNCHRONISATION_PRODUCT} checked="checked"{/if} />
		<label class="t" for="SYNCHRONISATION_PRODUCT_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="SYNCHRONISATION_PRODUCT" id="SYNCHRONISATION_PRODUCT_off" value="0"{if !$SYNCHRONISATION_PRODUCT} checked="checked"{/if} />
		<label class="t" for="SYNCHRONISATION_PRODUCT_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
		<br class="clear"/>
		<a target="_blank" href="{$cron_product_url|escape:'htmlall':'UTF-8'}&f=1"><input style="margin-top:10px;" type="button" value="{l s='Forcer la synchronisation des produits' mod='nqgatewayneteven'}"/></a>
	</div>

	<label>{l s='URL de synchronisation' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<span >{$cron_product_url|escape:'htmlall':'UTF-8'}</span>
	</div>

	<label>{l s='Exporter seulement les produit actif' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SYNCHRO_PRODUCT_ONLY_ACTIVE"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_ONLY_ACTIVE} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_ONLY_ACTIVE} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>

	<label>{l s='Exporter les produit hors stock' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SYNCHRO_PRODUCT_OOS"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_OOS} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_OOS} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>

	<label>{l s='Exporter les informations globales' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SYNCHRO_PRODUCT_PARENT"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_PARENT} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_PARENT} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
		<span>{l s='Envoi des attributs du produit parent sir vide qsur la produit décliné' mod='nqgatewayneteven'}</span>
	</div>

	<br/><hr/>
	<label>{l s='Nom de la marque par défaut' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="DEFAULT_BRAND" value="{$DEFAULT_BRAND|escape:'htmlall':'UTF-8'}" /> <em>({l s='utilisé pour les produits sans marque' mod='nqgatewayneteven'})</em>
	</div>
	<label>{l s='Format des images produit' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="IMAGE_TYPE_NAME">
			<option value="">{l s='--' mod='nqgatewayneteven'}</option>';
			{foreach from=$format_images item=format_image}
				<option value="{$format_image.name|escape:'htmlall':'UTF-8'}"{if $format_image.name == $IMAGE_TYPE_NAME} selected="selected"{/if}>{$format_image.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>
	</div>

	<label>{l s='Type SKU' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="radio" name="TYPE_SKU" id="TYPE_SKU_ref" value="reference"{if $TYPE_SKU == 'reference'} checked="checked"{/if} />
		<label class="t" for="TYPE_SKU_ref">{l s='référence' mod='nqgatewayneteven'}</label>
		<input type="radio" name="TYPE_SKU" id="TYPE_SKU_id" value="id"{if $TYPE_SKU == 'id'} checked="checked"{/if} />
		<label class="t" for="TYPE_SKU_id"> {l s='id produit / id déclinaison' mod='nqgatewayneteven'}</label>
		<br class="clear"/>
	</div>
	<br/><hr/>


	<label>{l s='Attributs à synchroniser' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="SYNCHRO_PRODUCT_OTHER_FIELDS[]" multiple="multiple" size="10">
			<optgroup label="{l s='Produit' mod='nqgatewayneteven'}">
				{foreach from=$SYNCHRO_PRODUCT_OTHER_FIELDS.product_fields item=field}
					<option value="P¤{$field|escape:'htmlall':'UTF-8'}" {if in_array($field, $SYNCHRO_PRODUCT_OTHER_FIELDS_P)}selected="selected" {/if}>{$field|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
				{foreach from=$SYNCHRO_PRODUCT_OTHER_FIELDS.product_lang_fields item=field}
					<option value="PL¤{$field|escape:'htmlall':'UTF-8'}" {if in_array($field, $SYNCHRO_PRODUCT_OTHER_FIELDS_PL)}selected="selected" {/if}>{$field|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</optgroup>
			<optgroup label="{l s='Features' mod='nqgatewayneteven'}">
				{foreach from=$SYNCHRO_PRODUCT_OTHER_FIELDS.feature_fields item=field}
					<option value="F¤{$field.id_feature|intval}" {if in_array($field.id_feature, $SYNCHRO_PRODUCT_OTHER_FIELDS_F)}selected="selected" {/if}>{$field.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</optgroup>
			<optgroup label="{l s='Attributes' mod='nqgatewayneteven'}">
				{foreach from=$SYNCHRO_PRODUCT_OTHER_FIELDS.attribute_fields item=field}
					<option value="A¤{$field.id_attribute_group|intval}" {if in_array($field.id_attribute_group, $SYNCHRO_PRODUCT_OTHER_FIELDS_A)}selected="selected" {/if}>{$field.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</optgroup>
		</select>
	</div>
	<br/>



	{foreach from=$t_fields key=field_name item=field}
		<label>{$field_name|escape:'htmlall':'UTF-8'}</label>
		<div class="margin-form">
			<select class="{if $field.values_group == 'prices'}prices_select{/if}" name="SYNCHRO_PRODUCT_MATCH[{$field_name|escape:'htmlall':'UTF-8'}]">
				<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>';
				{if isset($t_values[$field.values_group])}
					{foreach from=$t_values[$field.values_group] key=name item=value}
						<option data-countryLinked="{if isset($value.categoryLinked) AND $value.categoryLinked}{$value.categoryLinked|escape:'htmlall':'UTF-8'}{else}0{/if}" value="{$name|escape:'htmlall':'UTF-8'}"{if $SYNCHRO_PRODUCT_MATCH[$field_name] AND $SYNCHRO_PRODUCT_MATCH[$field_name] == $name} selected="selected"{/if}>{$value.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{/if}
			</select>
			{if $field.values_group == 'prices'}
				<select name="SYNCHRO_PRODUCT_MATCH_COUNTRY[{$field_name|escape:'htmlall':'UTF-8'}]" style="{if empty($SYNCHRO_PRODUCT_MATCH[$field_name]) OR !$t_values[$field.values_group][$SYNCHRO_PRODUCT_MATCH[$field_name]].categoryLinked}display: none;{/if}">
					<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
					{foreach from=$countries item=country}
						<option value="{$country.id_country|escape:'htmlall':'UTF-8'}" {if $SYNCHRO_PRODUCT_MATCH_COUNTRY[$field_name] AND $SYNCHRO_PRODUCT_MATCH_COUNTRY[$field_name] == $country.id_country} selected="selected"{/if}>{$country.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			{/if}
		</div>
	{/foreach}

	<br class="clear"/>
	<label>{l s='Mapping fr' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="SYNCHRO_PRODUCT_LANG_FR">
			<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
			{foreach from=$languages item=language}
				<option value="{$language.id_lang|intval}" {if $SYNCHRO_PRODUCT_LANG.SYNCHRO_PRODUCT_LANG_FR == $language.id_lang}selected="selected"{/if} >{$language.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>

		{assign "field_index" "SYNCHRO_PRODUCT_LANG_ACTIVE_FR"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<label>{l s='Mapping en' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="SYNCHRO_PRODUCT_LANG_EN">
			<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
			{foreach from=$languages item=language}
				<option value="{$language.id_lang|intval}" {if $SYNCHRO_PRODUCT_LANG.SYNCHRO_PRODUCT_LANG_EN == $language.id_lang}selected="selected"{/if} >{$language.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>

		{assign "field_index" "SYNCHRO_PRODUCT_LANG_ACTIVE_EN"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<label>{l s='Mapping de' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="SYNCHRO_PRODUCT_LANG_DE">
			<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
			{foreach from=$languages item=language}
				<option value="{$language.id_lang|intval}" {if $SYNCHRO_PRODUCT_LANG.SYNCHRO_PRODUCT_LANG_DE == $language.id_lang}selected="selected"{/if} >{$language.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>

		{assign "field_index" "SYNCHRO_PRODUCT_LANG_ACTIVE_DE"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<label>{l s='Mapping es' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="SYNCHRO_PRODUCT_LANG_ES">
			<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
			{foreach from=$languages item=language}
				<option value="{$language.id_lang|intval}" {if $SYNCHRO_PRODUCT_LANG.SYNCHRO_PRODUCT_LANG_ES == $language.id_lang}selected="selected"{/if} >{$language.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>

		{assign "field_index" "SYNCHRO_PRODUCT_LANG_ACTIVE_ES"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<label>{l s='Mapping it' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select name="SYNCHRO_PRODUCT_LANG_IT">
			<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
			{foreach from=$languages item=language}
				<option value="{$language.id_lang|intval}" {if $SYNCHRO_PRODUCT_LANG.SYNCHRO_PRODUCT_LANG_IT == $language.id_lang}selected="selected"{/if} >{$language.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>

		{assign "field_index" "SYNCHRO_PRODUCT_LANG_ACTIVE_IT"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_PRODUCT_LANG[$field_index]} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>

	<br class="clear" />
	<center><input type="submit" name="submitSynchroProduct" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>

{***********************************************************************}
{*  Synchronisation du stock  *}
{***********************************************************************}
<fieldset style="margin-top: 15px;" >
	<legend><img src="../img/admin/cog.gif" alt="{l s='Synchronisation du stock' mod='nqgatewayneteven'}" class="middle" />{l s='Synchronisation du stock' mod='nqgatewayneteven'}</legend>
	<label>{l s='Actif' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SYNCHRONISATION_STOCK"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRONISATION_STOCK} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRONISATION_STOCK} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
		<br class="clear"/>
		<a target="_blank" href="{$cron_stock_url|escape:'htmlall':'UTF-8'}&f=1"><input style="margin-top:10px;" type="button" value="{l s='Forcer la synchronisation du stock' mod='nqgatewayneteven'}"/></a>
	</div>

	<label>{l s='URL de synchronisation' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<span >{$cron_stock_url|escape:'htmlall':'UTF-8'}</span>
	</div>

	<label>{l s='Exporter seulement les produit actif' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SYNCHRO_STOCK_ONLY_ACTIVE"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_STOCK_ONLY_ACTIVE} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_STOCK_ONLY_ACTIVE} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>

	<label>{l s='Exporter les produit hors stock' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SYNCHRO_STOCK_OOS"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SYNCHRO_STOCK_OOS} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SYNCHRO_STOCK_OOS} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>

	<br class="clear" />
	<center><input type="submit" name="submitSynchroStock" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>


{***********************************************************************}
{*  Synchronisation des commandes *}
{***********************************************************************}
<fieldset style="margin-top: 15px;" >
	<legend><img src="../img/admin/cog.gif" alt="{l s='Synchronisation des commandes' mod='nqgatewayneteven'}" class="middle" />{l s='Synchronisation des commandes' mod='nqgatewayneteven'}</legend>
	<label>{l s='Synchronisation des commandes' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="radio" name="SYNCHRONISATION_ORDER" id="SYNCHRONISATION_ORDER_on" value="1"{if $SYNCHRONISATION_ORDER} checked="checked"{/if} />
		<label class="t" for="SYNCHRONISATION_ORDER_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="SYNCHRONISATION_ORDER" id="SYNCHRONISATION_ORDER_off" value="0"{if !$SYNCHRONISATION_ORDER} checked="checked"{/if} />
		<label class="t" for="SYNCHRONISATION_ORDER_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
		<br class="clear"/>
		<a target="_blank" href="{$cron_order_url|escape:'htmlall':'UTF-8'}&f=1"><input style="margin-top:10px;" type="button" value="{l s='Forcer la synchonisation des commandes' mod='nqgatewayneteven'}"/></a>
	</div>

	<br />
	<label>{l s='URL de synchronisation' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<span >{$cron_order_url|escape:'htmlall':'UTF-8'}</span>
	</div>

	<div class="warning">
		{l s='Ajouter des stauts de commandes avant ou après le statut de commande NetEven' mod='nqgatewayneteven'}<br />
		{l s='Par exmemple, cela vous permet de passer directement une commande dans le statut en "Paiement accepté" ou "En cours de livraison" pour tous les commandes provenant de NetEven' mod='nqgatewayneteven'}
	</div>
	<br /><br />

	<label>{l s='Ajouter des statuts définis avant' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="hidden" name="id_state_before" value=""/>
		<select id="select_before" >
			{foreach from=$order_states item=order_state}
				{if $order_state.id_order_state != $ID_ORDER_STATE_NETEVEN}
					<option value="{$order_state.id_order_state|intval}" >{$order_state.name|escape:'htmlall':'UTF-8'}</option>
				{/if}
			{/foreach}
		</select>
		<input type="button" class="button" value="{l s='Ajouter' mod='nqgatewayneteven'}" id="add_before"/>
	</div>
	<div id="state_before" style="margin-left:40px;">
		<div>
			<ul class="order_state_list"></ul>
		</div>
	</div><br /><br />

	<label>{l s='Ajouter des statuts définis après' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="hidden" name="id_state_after" value=""/>
		<select id="select_after" >';
			{foreach from=$order_states item=order_state}
				{if $order_state.id_order_state != $ID_ORDER_STATE_NETEVEN}
					<option value="{$order_state.id_order_state|intval}" >{$order_state.name|escape:'htmlall':'UTF-8'}</option>
				{/if}
			{/foreach}
		</select>
		<input type="button" class="button" value="{l s='Ajouter' mod='nqgatewayneteven'}" id="add_after"/>
	</div>
	<div id="state_after" style="margin-left:40px;">
		<div>
			<ul class="order_state_list"></ul>
		</div>
	</div>

	<br class="clear" />
	<center><input type="submit" name="submitNetEven" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>

{***********************************************************************}
{*  Mapping statut commande Neteven -> Presta *}
{***********************************************************************}
<fieldset style="margin-top: 15px;">
	<legend><img src="../img/admin/cog.gif" alt="" class="middle" />{l s='Mapping Neteven -> Prestashop' mod='nqgatewayneteven'}</legend>
		{foreach from=$t_neteven_order_state key=name item=neteven_state}
			<label>{$neteven_state.name|escape:'htmlall':'UTF-8'}</label>
			<div class="margin-form">
				{assign "neteven_state_index" $neteven_state.index}
				{assign "field_index" "MAPPING_STATE_$neteven_state_index"}
				<select name="{$field_index|escape:'htmlall':'UTF-8'}">
					<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
					{foreach from=$t_order_state item=order_state}
						<option value="{$order_state.id_order_state|intval}" {if $order_state.id_order_state == $neteven_state.config}selected="selected"{/if} >{$order_state.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</div>
			<div class="clear"></div>
		{/foreach}
	<br class="clear" />
	<center><input type="submit" name="submitMappingNetevenPresta" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>


{***********************************************************************}
{*  Mapping statut commande Presta -> Neteven *}
{***********************************************************************}
<fieldset style="margin-top: 15px;">
	<legend><img src="../img/admin/cog.gif" alt="" class="middle" />{l s='Mapping Prestashop -> Neteven' mod='nqgatewayneteven'}</legend>

	{foreach from=$t_order_state item=order_state}
		<label>{$order_state.name|escape:'htmlall':'UTF-8'}</label>
		<div class="margin-form">
			{assign "order_state_id" $order_state.id_order_state}
			{assign "field_index" "MAPPING_STATE_$order_state_id"}
			<select name="{$field_index|escape:'htmlall':'UTF-8'}">
				<option value="0">{$default_select_value|escape:'htmlall':'UTF-8'}</option>
				{foreach from=$t_neteven_order_state key=name item=neteven_state}
					{if $neteven_state.can_use}
						<option value="{$name|escape:'htmlall':'UTF-8'}" {if $order_state.neteven_state == $name}selected="selected"{/if} >{$name|escape:'htmlall':'UTF-8'}</option>
					{/if}
				{/foreach}
			</select>
		</div>
		<div class="clear"></div>
	{/foreach}

	<br class="clear" />
	<center><input type="submit" name="submitMappingPrestaNeteven" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>

{***********************************************************************}
{*  Livraison  *}
{***********************************************************************}
<fieldset style="margin-top: 15px;" >
	<legend><img src="../img/admin/cog.gif" alt="" class="middle" />{l s='Livraison' mod='nqgatewayneteven'}</legend>
	<label>{l s='Actif ' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "ACTIVE_SHIPPING"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $ACTIVE_SHIPPING} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$ACTIVE_SHIPPING} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<label>{l s='Délai de livraison' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="SHIPPING_DELAY" value="{$SHIPPING_DELAY|intval}" /> <em>({l s='en jours' mod='nqgatewayneteven'})</em>
	</div>

	<label>{l s='Informations sur la livraison' mod='nqgatewayneteven'}</label>
	{assign var='defaultFormLanguage' value=1}
	{assign var='default_language' value='fr'}
	<div class="margin-form">
		<div class="translatable">
			{foreach from=$languages item=language}
				<div id="meta_title_{$language.id_lang|intval}" style=" float: left;"> {* display: {if $language.id_lang == $defaultFormLanguage}block{else}none{/if}; *}
					<input size="40" type="text" id="COMMENT_LANG_{$language.id_lang|intval}" name="COMMENT_LANG_{$language.id_lang|intval}"
					       value="{$COMMENT_LANG[$language.id_lang|intval]}" />
					<img src="../img/l/{$language.id_lang|intval}.jpg" class="pointer" alt="{$language.name|escape}" title="{$language.name|escape}" />
				</div>
				<div class="clear"></div>
			{/foreach}
		</div>
	</div>
	<script type="text/javascript">
		{literal}
		$(document).ready(function(){
			id_language = {/literal}{$defaultFormLanguage|intval}{literal};
			languages = new Array();
			{/literal}{foreach from=$languages item=language name=langLoop}{literal}
				languages[{/literal}{$smarty.foreach.langLoop.index|intval}{literal}] = {
					id_lang: '{/literal}{$language.id_lang|intval}{literal}',
					iso_code: '{/literal}{$language.iso_code|escape:'htmlall':'UTF-8'}{literal}',
					name: '{/literal}{$language.name|escape:'htmlall':'UTF-8'}{literal}'
				};
			{/literal}{/foreach}{literal}
			//displayFlags(languages, id_language, 0);
		});
		{/literal}
	</script>
	<br/>
	<div class="clear"></div>

	<label>{l s='Frais de livraison' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="SHIPPING_PRICE_LOCAL" value="{$SHIPPING_PRICE_LOCAL|floatval}" /> <em>({l s='en' mod='nqgatewayneteven'} {$default_currency->sign|escape:'htmlall':'UTF-8'})</em>
	</div>
	<label>{l s='Frais de livraison à l\'international' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="SHIPPING_PRICE_INTERNATIONAL" value="{$SHIPPING_PRICE_INTERNATIONAL|floatval}" /> <em>({l s='en' mod='nqgatewayneteven'} {$default_currency->sign|escape:'htmlall':'UTF-8'})</em>
	</div>

	<label>{l s='Frais de port par produit' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="radio" name="SHIPPING_BY_PRODUCT" id="SHIPPING_BY_PRODUCT_on" value="1"{if $SHIPPING_BY_PRODUCT} checked="checked"{/if} />
		<label class="t" for="SHIPPING_BY_PRODUCT_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="SHIPPING_BY_PRODUCT" id="SHIPPING_BY_PRODUCT_off" value="0"{if !$SHIPPING_BY_PRODUCT} checked="checked"{/if} />
		<label class="t" for="SHIPPING_BY_PRODUCT_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
		<em>({l s='Permet de définir un montant de frais de port fixe par produit' mod='nqgatewayneteven'})</em>
	</div>
	<div id="shipping_fieldname_container"{if !$SHIPPING_BY_PRODUCT} style="display:none;"{/if}>
		<div class="warning">
		{l s='N\'utiliser cette fonctionnalité que si vous êtes sûr de vous !' mod='nqgatewayneteven'}<br />
		{l s='Renseigner ci-dessous le nom du champs en base de donnée contenant la valeur de frais de port des produits, doit être dans la table `product`' mod='nqgatewayneteven'}
		</div>
		<label>{l s='Nom du champs de frais de port par produit' mod='nqgatewayneteven'}</label>
		<div class="margin-form">
			<input type="text" name="SHIPPING_BY_PRODUCT_FIELDNAME" value="{$SHIPPING_BY_PRODUCT_FIELDNAME|escape:'htmlall':'UTF-8'}" />
		</div>
	</div>
	<br class="clear" />

	<div>
		<h4>{l s='Vos transporteurs pour calculer les frais de ports' mod='nqgatewayneteven'}</h4>

		<h5>{l s='Local' mod='nqgatewayneteven'}</h5>

		<label for="country_france">{l s='Pays local' mod='nqgatewayneteven'}</label>
		<div class="margin-form">
			<select name="SHIPPING_COUNTRY_FRANCE" id="country_france">
				<option value="Autre" >{l s='Autre pays'}</option>
				{foreach from=$countries item=country}
					<option value="{$country.id_country|intval}" {if $SHIPPING_COUNTRY_FRANCE == $country.id_country}selected="selected"{/if}>{$country.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		</div>

		<label>{l s='Transporteur' mod='nqgatewayneteven'}</label>
		<div class="margin-form">
			<select name="SHIPPING_CARRIER_FRANCE" id="carrier_france">
				<option value="" >---------</option>
			{foreach from=$carriers item=carrier}
				<option value="{$carrier.id_carrier|intval}" {if $SHIPPING_CARRIER_FRANCE == $carrier.id_carrier}selected="selected"{/if}>{$carrier.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
			</select>
		</div>
		<label>{l s='Zone' mod='nqgatewayneteven'}</label>
		<div class="margin-form" id="zone_france">{l s='Selecionner une transporteur pour voir les zones' mod='nqgatewayneteven'}</div>
		<br class="clear" />

		<h5>{l s='Internationnal' mod='nqgatewayneteven'}</h5>

		<label>{l s='Transporteur' mod='nqgatewayneteven'}</label>
		<div class="margin-form">
			<select name="SHIPPING_CARRIER_INTERNATIONAL" id="carrier_international">
				<option value="" >---------</option>
			{foreach from=$carriers item=carrier}
				<option value="{$carrier.id_carrier|intval}" {if $SHIPPING_CARRIER_INTERNATIONAL == $carrier.id_carrier}selected="selected"{/if}>{$carrier.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
			</select>
		</div>
		<label>{l s='Zone' mod='nqgatewayneteven'}</label>
		<div class="margin-form" id="zone_international">{l s='Selecionner une transporteur pour voir les zones' mod='nqgatewayneteven'}</div>
		<br class="clear" />

	</div>
	<br class="clear" />

	<center><input type="submit" name="submitNetEvenShipping" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>

{***********************************************************************}
{*  Mapping features  *}
{***********************************************************************}
{*<fieldset style="margin-top: 15px;" >
	<legend><img src="../img/admin/cog.gif" alt="" class="middle" />{l s='Liens entre les caractéristiques PrestaShop et NetEven' mod='nqgatewayneteven'}</legend>

{if !$neteven_feature_categories}
	<div class="error">{l s='Aucune catégorie disponible actuellement, veuillez lancer manuellement le script ci-dessous' mod='nqgatewayneteven'}</div>
{/if}

	<a target="_blank" href="{$cron_feature_url|escape:'htmlall':'UTF-8'}"><input style="margin-top:10px;" type="button" value="{l s='Synchonisation les caractéristiques NetEven' mod='nqgatewayneteven'}"/></a>
	<br /><br />

	<label>{l s='Caractéristiques PrestaShop' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select id="select_feature" >';
		{if $features}
			{foreach from=$features item=feature}
				<option value="F{$feature.id_feature|intval}">{$feature.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		{/if}
		{if $attribute_groups}
			{foreach from=$attribute_groups item=attribute_group}
				<option value="A{$attribute_group.id_attribute_group|intval}">{$attribute_group.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		{/if}
		</select>
	</div>

	<label>{l s='Catégories de caractéristique NetEven' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select id="select_carac">';
			<option value="">{l s='--' mod='nqgatewayneteven'}</option>
		{foreach from=$neteven_feature_categories item=neteven_features key=neteven_feature_category_name}
			<option value="{$neteven_feature_category_name|escape:'htmlall':'UTF-8'}" >{$neteven_feature_category_name|escape:'htmlall':'UTF-8'}</option>
		{/foreach}
		</select>
	</div>
	<label>{l s='Caractéristiques NetEven' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<select class="attr_neteven"><option>{l s='--' mod='nqgatewayneteven'}</option></select>
	{foreach from=$neteven_feature_categories item=neteven_features key=neteven_feature_category_name}
		<select rel="{$neteven_feature_category_name|escape:'htmlall':'UTF-8'}" style="display:none" class="attr_neteven">
			{foreach from=$neteven_features item=neteven_feature}
				<option value="{$neteven_feature.id_order_gateway_feature|intval}" >{$neteven_feature.name|escape:'htmlall':'UTF-8'}</option>
			{/foreach}
		</select>
	{/foreach}
	</div>
	<center class="clear"><input type="button" class="button" value="{l s='Ajouter' mod='nqgatewayneteven'}" id="add_link"/></center>
	<br /><br />

	<div id="link" style="margin-left: 40px;">
		<label style="float:none; width:auto;">{l s='Liste des liaisons de caractéristiques PrestaShop / NetEven' mod='nqgatewayneteven'}</label>
		<br />
		<div style="display:block;">
			<ul class="feature_links"></ul>
		</div>
	</div>
</fieldset>*}

{***********************************************************************}
{*  Others fields  *}
{***********************************************************************}
<fieldset style="margin-top: 15px;">
	<legend><img src="../img/admin/cog.gif" alt="" class="middle" />{l s='Champs supplémentaire personnalisés' mod='nqgatewayneteven'}</legend>
	<a onclick="addCustomizableField()"><img src="../img/admin/add.gif" border="0"> {l s='Ajouter un champs' mod='nqgatewayneteven'}</a><br /><br />
	<div id="customizable">
	{if $customizable_fields|@count > 0}
		{foreach from=$customizable_fields item=customizable_field}
			<label>{l s='Nom du champs' mod='nqgatewayneteven'}</label>
			<div class="margin-form">
				<input type="text" name="customizable_field_name[]" value="{$customizable_field.0|escape:'htmlall':'UTF-8'}" />
			</div>
			<label>{l s='Valeur' mod='nqgatewayneteven'}</label>
			<div class="margin-form">
				<input type="text" name="customizable_field_value[]" value="{$customizable_field.1|escape:'htmlall':'UTF-8'}" />
			</div>
			<hr />
		{/foreach}
	{/if}

		<label>{l s='Nom du champs' mod='nqgatewayneteven'}</label>
		<div class="margin-form">
			<input type="text" name="customizable_field_name[]" value="" />
		</div>
		<label>{l s='Valeur' mod='nqgatewayneteven'}</label>
		<div class="margin-form">
			<input type="text" name="customizable_field_value[]" value="" />
		</div>
		<hr />
	</div>
	<br class="clear" />
	<center><input type="submit" name="submitCustomizableFeilds" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>

{***********************************************************************}
{*  Maintenance et Debug  *}
{***********************************************************************}
<fieldset style="margin-top: 15px;">
	<legend><img src="../img/admin/cog.gif" alt="" class="middle" />{l s='Maintenance' mod='nqgatewayneteven'}</legend>

	{*<label>{l s='URL NetEven' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="NETEVEN_URL" value="{$NETEVEN_URL|escape:'htmlall':'UTF-8'}" />
	</div>
	<label>{l s='NS NetEven' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="NETEVEN_NS" value="{$NETEVEN_NS|escape:'htmlall':'UTF-8'}" />
	</div>
	<br /><br />*}

	<label>{l s='Mode sand box' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		{assign "field_index" "SAND_BOX"}
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_on" value="1"{if $SAND_BOX} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="{$field_index|escape:'htmlall':'UTF-8'}" id="{$field_index|escape:'htmlall':'UTF-8'}_off" value="0"{if !$SAND_BOX} checked="checked"{/if} />
		<label class="t" for="{$field_index|escape:'htmlall':'UTF-8'}_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>

	<label>{l s='Mode debug' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="radio" name="DEBUG" id="DEBUG_on" value="1"{if $DEBUG} checked="checked"{/if} />
		<label class="t" for="DEBUG_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="DEBUG" id="DEBUG_off" value="0"{if !$DEBUG} checked="checked"{/if} />
		<label class="t" for="DEBUG_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<label>{l s='Envoyer les requêtes NetEven par email' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="radio" name="SEND_REQUEST_BY_EMAIL" id="SEND_REQUEST_BY_EMAIL_on" value="1"{if $SEND_REQUEST_BY_EMAIL} checked="checked"{/if} />
		<label class="t" for="SEND_REQUEST_BY_EMAIL_on"> <img src="../img/admin/enabled.gif" alt="{l s='Oui' mod='nqgatewayneteven'}" title="{l s='Oui' mod='nqgatewayneteven'}" /></label>
		<input type="radio" name="SEND_REQUEST_BY_EMAIL" id="SEND_REQUEST_BY_EMAIL_off" value="0"{if !$SEND_REQUEST_BY_EMAIL} checked="checked"{/if} />
		<label class="t" for="SEND_REQUEST_BY_EMAIL_off"> <img src="../img/admin/disabled.gif" alt="{l s='Non' mod='nqgatewayneteven'}" title="{l s='Non' mod='nqgatewayneteven'}" /></label>
	</div>
	<br class="clear" />
	<label>{l s='Liste des adresses email d\'alertes' mod='nqgatewayneteven'}</label>
	<div class="margin-form">
		<input type="text" name="MAIL_LIST_ALERT" value="{$MAIL_LIST_ALERT|escape:'htmlall':'UTF-8'}" /> <em>({l s='séparées par le caractère :' mod='nqgatewayneteven'})</em>
	</div>

	<br class="clear" />
	<center><input type="submit" name="submitDev" value="{l s='Enregistrer' mod='nqgatewayneteven'}" class="button" /></center>
</fieldset>
</form>