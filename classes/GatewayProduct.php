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

class GatewayProduct extends Gateway
{
	public static $type_sku = 'reference';
	public static $active_shipping = false;
	public static $shipping_by_product = false;
	public static $shipping_by_product_fieldname = false;
	public static $customizable_field = array();

	public static $product_export_only_active = 0;
	public static $product_export_oos = 0;
	public static $product_export_parent_info = 0;

	public static $stock_export_only_active = 0;
	public static $stock_export_oos = 0;

	public static $synchro_product_total = 0;
	public static $synchro_product_current = 0;
	public static $synchro_product_lot = 0;
	public static $synchro_product_lot_total = 0;

	public static $cache_feature_other_fields = array();

	protected $ids_inactive = array();

	/* @var array List of Gateway instance */
	protected static $instance = array();

	public static function getInstance($client = null)
	{
		$wsdl = 0;

		if ($client != null)
			$wsdl = 1;

		if (!isset(self::$instance[$wsdl]))
			self::$instance[$wsdl] = new GatewayProduct($client);

		self::$type_sku = (Gateway::getConfig('TYPE_SKU') !== false) ? Gateway::getConfig('TYPE_SKU') : 'reference';

		return self::$instance[$wsdl];
	}

	public function __construct($client = null)
	{
		parent::__construct($client);
		self::$active_shipping = Gateway::getConfig('ACTIVE_SHIPPING');
		self::$shipping_by_product = Gateway::getConfig('SHIPPING_BY_PRODUCT');
		self::$shipping_by_product_fieldname = Gateway::getConfig('SHIPPING_BY_PRODUCT_FIELDNAME');

		// Load export product config vars //
		self::$product_export_only_active = (int)Gateway::getConfig('SYNCHRO_PRODUCT_ONLY_ACTIVE');
		self::$product_export_oos = (int)Gateway::getConfig('SYNCHRO_PRODUCT_OOS');
		self::$product_export_parent_info = (int)Gateway::getConfig('SYNCHRO_PRODUCT_PARENT');

		self::$stock_export_only_active = (int)Gateway::getConfig('SYNCHRO_STOCK_ONLY_ACTIVE');
		self::$stock_export_oos = (int)Gateway::getConfig('SYNCHRO_STOCK_OOS');

		if (Gateway::getConfig('CUSTOMIZABLE_FIELDS'))
		{
			$customizable_fields = explode('¤', Gateway::getConfig('CUSTOMIZABLE_FIELDS'));
			foreach ($customizable_fields as $customizable_field)
			{
				$customizable_value = explode('|', $customizable_field);
				self::$customizable_field[$customizable_value[0]] = $customizable_value[1];
			}
		}
	}

	/**
	 * Méthode de synchronisation de l'inventaire.
	 */
	public function updateProduct($is_display = true)
	{
		$indice = 0;
		$products = $this->getAllProductAcreer(array(), $indice);

		if ($this->getValue('debug'))
			Toolbox::displayDebugMessage(self::getL('Quantity of recovered product').' : '.count($products));

		if ($this->getValue('debug'))
			Toolbox::displayDebugMessage(self::getL('Quantity of recovered product after removing products without EAN code').' : '.count($products));

		$security = 0;
		$control_while = true;

		while ($control_while)
		{
			$neteven_products = $this->getPropertiesForNetEven($products, $is_display);

			if ($is_display)
				Tools::p($neteven_products);

			if (!$is_display)
				$this->addProductInNetEven($neteven_products);

			$indice++;
			$products_base = $this->getAllProductAcreer(array(), $indice);

			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('Quantity of recovered product').' : '.count($products_base));

			$products = $products_base;

			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('Quantity of recovered product after removing products without EAN code').' : '.count($products));

			$security++;
			if ($security > 1000)
				$control_while = false;

			if ((is_array($products_base) && count($products_base) == 0) || !is_array($products_base))
				$control_while = false;

			self::$synchro_product_lot++;
		}
	}

	/**
	 * Récupération de tous les produits du presta.
	 *
	 * @param array $product_exlusion
	 * @param int $indice
	 * @return mixed
	 */
	private function getAllProductAcreer($products_exlusion = array(), $indice = 0)
	{
		$context = Context::getContext();

		if ($this->getValue('debug'))
			$neteven_date_export_product = '';
		else
			$neteven_date_export_product = Configuration::get('neteven_date_export_product');

		$separator = $this->getValue('separator');

		$id_lang = isset($context->cookie->id_lang) ? (int)$context->cookie->id_lang : (int)Configuration::get('PS_LANG_DEFAULT');
		$sql = 'SELECT SQL_CALC_FOUND_ROWS
			'.(self::$shipping_by_product && !empty(self::$shipping_by_product_fieldname) ? 'p.`'.pSQL(self::$shipping_by_product_fieldname).'`,' : '').'
			ip.id_product as id_product_inactive,
			pl.`link_rewrite`,
			p.`id_category_default`,
			p.`id_product`,
			p.`active`,
			p.`available_for_order`,
			pl.`name`,
			pl.`description`,
			p.`id_category_default` as id_category,
			cl.`name` as category_name,
			p.`ean13`,
			pa.`ean13` as ean13_declinaison,
			p.`upc`,
			pa.`upc` as upc_declinaison,
			IFNULL(pa.`Wholesale_price`, p.`Wholesale_price`) as wholesale_price,
			pa.`id_product_attribute` as id_product_attribute,
			p.`quantity`,
			pa.`quantity` as pa_quantity,
			p.`wholesale_price`,
			p.`condition`,
			pl.`meta_keywords`,
			m.`name` as name_manufacturer,
			p.`reference` as product_reference,
			pa.`reference` as product_attribute_reference,
			p.`additional_shipping_cost`,
			p.`height`,
			p.`width`,
			p.`depth`,
			p.`weight`,
			pa.`weight` as weight_product_attribute,
			GROUP_CONCAT(distinct CONCAT(agl.`name`," {##} ",al.`name`) SEPARATOR "'.pSQL($separator).' ") as attribute_name
			'.((self::$type_sku != 'reference') ? ',(SELECT CONCAT(\'D\', pa2.`id_product_attribute`) FROM `'._DB_PREFIX_.'product_attribute` pa2 WHERE pa2.`id_product` = p.`id_product` AND `default_on` = 1 LIMIT 1) as declinaison_default' : '').'
				'.((self::$type_sku == 'reference') ? ',(SELECT pa2.`reference` FROM `'._DB_PREFIX_.'product_attribute` pa2 WHERE pa2.`id_product` = p.`id_product` AND `default_on` = 1 LIMIT 1) as declinaison_default_ref' : '').'
		FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cl.`id_category` = p.`id_category_default` AND cl.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = p.`id_product`)
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute`=pa.`id_product_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute`=pac.`id_attribute`)
		LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (al.`id_attribute`=a.`id_attribute` AND al.`id_lang`='.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (agl.`id_attribute_group`=a.`id_attribute_group` AND agl.`id_lang`='.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'orders_gateway_inactive_product` ip ON (ip.id_product = p.id_product)
		WHERE 1 '.((self::$product_export_only_active && !Gateway::getConfig('SYNCHRO_PRODUCT_CHANGE_ACTIVE')) ? ' AND ((p.`active` = 1 AND p.`available_for_order` = 1 ) OR ip.id_product IS NOT NULL)' : '').'
		'.((is_array($products_exlusion) && count($products_exlusion) > 0) ? ' AND (p.`reference` NOT IN ('.implode(',', pSQL($products_exlusion)).') AND pa.`reference` NOT IN ('.implode(',', pSQL($products_exlusion)).'))' : '');

		$sql .= '
		GROUP BY p.`id_product`, pa.`id_product_attribute`
		LIMIT '.($indice * 100).', 100
		';

		if ($this->getValue('debug'))
			Toolbox::displayDebugMessage($sql);

		$products = Db::getInstance()->ExecuteS($sql);

		if (empty(self::$synchro_product_lot_total))
		{
			self::$synchro_product_total = Db::getInstance()->getValue('SELECT FOUND_ROWS() AS NbRows');
			self::$synchro_product_lot_total = self::$synchro_product_total / 100 + 1;
			self::$synchro_product_lot = 1;
		}

		Toolbox::addLogLine(self::getL('Product to update or create').' '.count($products));
		Toolbox::writeLog();

		return $products;
	}

	/**
	 * Formatage des informations produit pour NetEven.
	 *
	 * @param $t_product
	 * @param bool $display
	 * @return array
	 */
	private function getPropertiesForNetEven($products, $display = false)
	{
		if (!count($products))
			return false;

		$context = Context::getContext();

		$link = new Link();

		$products_temp = array();

		$compteur_product_no_ean13 = 0;
		$compteur_product_no_ref = 0;
		foreach ($products as $product)
		{
			self::$synchro_product_current++;
			Gateway::setStepAjaxCron(2, 'Processed : '.(int)ceil((self::$synchro_product_current * 100) / self::$synchro_product_total).' % - lot en cours : '.self::$synchro_product_lot.' / '.(int)self::$synchro_product_lot_total, Tools::getValue('uniqkey'));

			/*
			 * GET NETEVEN LANGUAGES
			 */
			$neteven_languages = self::getNetevenLanguages(true);
			$t_languages = array();
			$translate_to_neteven = array();
			foreach ($neteven_languages as $language)
				if (!empty($language['config']) && $language['active'])
				{
					$t_languages[] = $language['config'];
					$translate_to_neteven[$language['config']] = $language['name'];
				}

			/*
			 * Quantity
			 */
			$quantity = Product::getQuantity((int)$product['id_product'], !empty($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
			if (!self::$product_export_oos && (int)$quantity <= 0)
				if (!Gateway::getConfig('SYNCHRO_PRODUCT_CHANGE_OOS'))
					continue;
			$quantity = 0;

			if (self::$product_export_only_active && Gateway::getConfig('SYNCHRO_PRODUCT_CHANGE_ACTIVE') && (!$product['active'] || !$product['available_for_order']))
				$quantity = 0;

			if (!empty($product['id_product_inactive']))
				$this->ids_inactive[] = $product['id_product_inactive'];

			/*
			 * Reference
			 */
			$product_reference = 'P'.$product['id_product'];
			if (!empty($product['id_product_attribute']))
				$product_reference = 'D'.$product['id_product_attribute'];

			if (self::$type_sku == 'reference')
			{
				$product_reference = $product['product_reference'];
				if (!empty($product['id_product_attribute']))
					$product_reference = $product['product_attribute_reference'];

			}

			/*
			 * Code EAN
			 */
			$ean_ps = !empty($product['ean13_declinaison']) ? $product['ean13_declinaison'] : $product['ean13'];
			$codeEan = '';
			if (!empty($ean_ps))
				$codeEan = sprintf('%013s', $ean_ps);

			/*
			 * Code UPC
			 */
			$code_upc = !empty($product['upc_declinaison']) ? $product['upc_declinaison'] : $product['upc'];

			/*
			 * Attribute ID
			 */
			$id_product_attribute = null;
			if (!empty($product['id_product_attribute']))
				$id_product_attribute = (int)$product['id_product_attribute'];

			/*
			 * Categories
			 */
			$categories = $this->getProductCategories($product);
			$categories = array_reverse($categories);
			$classification = str_replace('//', '', implode('/', $categories));

			/*
			 * Weight
			 */
			$weight = $product['weight'];
			if (!empty($id_product_attribute))
				$weight += $product['weight_product_attribute'];

			/*
			 * SKU family
			 */
			if (self::$type_sku == 'reference')
			{
				if (empty($id_product_attribute))
					$sku_family = '';
				else
					$sku_family = $product['declinaison_default_ref'];
			}
			else
			{
				if (!empty($product['declinaison_default']))
					$sku_family = $product['declinaison_default'];
				else
					$sku_family = '';
			}

			$indice = count($products_temp);
			$products_temp[$indice] = array(
				'Title' => $product['name'],
				'Description' => strip_tags($product['description']),
				'SKU' => $product_reference,
				'Quantity' => $quantity,
				'SKUFamily' => $sku_family,
				'Classification' => str_replace('Accueil/', '', $classification),
				'Weight' => $weight,
				'Brand' => !empty($product['name_manufacturer']) ? $product['name_manufacturer'] : $this->getValue('default_brand')
			);

			// On ne prend pas le SKU family si le produit n'a pas de déclinaison.
			if (empty($products_temp[$indice]['SKUFamily']))
				unset($products_temp[$indice]['SKUFamily']);

			// On ne prend pas les champ de base (titre, description) si il n'y a pas de mappage pour la langue fr.
			if (!in_array('fr', $translate_to_neteven))
			{
				unset($products_temp[$indice]['Title']);
				unset($products_temp[$indice]['Description']);
			}

			list($t_values, $fields) = Gateway::getFieldsMatchTab();
			foreach ($fields as $name => $field)
			{
				$config = Gateway::getConfig('SYNCHRO_PRODUCT_MATCH_'.$name);
				$product['id_country'] = Gateway::getConfig('SYNCHRO_PRODUCT_MATCH_COUNTRY_'.$name);
				if (!empty($config))
				{
					$type = $t_values[$field['values_group']][$config]['type'];
					if ($type == 'function')
					{
						//-------------------------
						//- function callback
						//-------------------------
						$currency_id = '';
						if ((int)$product['id_country'])
							$currency_id = $this->getCurrencyIsoForProduct($product['id_country']);

						if (!empty($currency_id))
							$products_temp[$indice][$name] = array(
								'_' => $this->{$t_values[$field['values_group']][$config]['callback']}($product),
								'currency_id' => $this->getCurrencyIsoForProduct($product['id_country'])
							);
						else
							$products_temp[$indice][$name] = $this->{$t_values[$field['values_group']][$config]['callback']}($product);
					}
					else
					{
						switch ($config)
						{
							//-------------------------
							//- Identifier produit
							case 'ean13' :
								$content = $codeEan;
								break;
							case 'upc' :
								$content = '';
								break;
							case 'reference' :
								$content = $product_reference;
								break;
							case 'wholesale_price':
								$content = $product['wholesale_price'];
								break;
							case 'height':
								$content = $product['height'];
								break;
							case 'width':
								$content = $product['width'];
								break;
							case 'depth':
								$content = $product['depth'];
								break;
							//-------------------------
							//- Others vars

						}

						$products_temp[$indice][$name] = $content;
					}
				}
			}

			/*
			 * Shipping part
			 */
			if (self::$active_shipping)
			{
				$products_temp[$indice]['shipping_delay'] = $this->getValue('shipping_delay');
				$products_temp[$indice]['Comment'] = $this->getValue('comment');

				$shipping_price_local = $this->getValue('shipping_price_local');
				if (self::$shipping_by_product && !empty(self::$shipping_by_product_fieldname))
					$shipping_price_local = $product[self::$shipping_by_product_fieldname];

				$carrier_france = $this->getConfig('SHIPPING_CARRIER_FRANCE');
				$carrier_zone_france = $this->getConfig('SHIPPING_ZONE_FRANCE');

				if (!empty($carrier_france) && !empty($carrier_zone_france))
					$products_temp[$indice]['PriceShippingLocal1'] = $this->getShippingPrice($product['id_product'], $id_product_attribute, $carrier_france, $carrier_zone_france);
				elseif (!empty($shipping_price_local))
					$products_temp[$indice]['PriceShippingLocal1'] = $shipping_price_local;

				$shipping_price_inter = $this->getValue('shipping_price_international');
				$carrier_inter = $this->getConfig('SHIPPING_CARRIER_INTERNATIONAL');
				$carrier_zone_inter = $this->getConfig('SHIPPING_ZONE_INTERNATIONAL');

				if (!empty($carrier_france) && !empty($carrier_zone_france))
					$products_temp[$indice]['PriceShippingInt1'] = $this->getShippingPrice($product['id_product'], $id_product_attribute, $carrier_inter, $carrier_zone_inter);
				elseif (!empty($shipping_price_inter))
					$products_temp[$indice]['PriceShippingInt1'] = $shipping_price_inter;

				if (!empty($carrier_france) && !empty($carrier_zone_france))
					$products_temp[$indice]['PriceShippingInt1'] = $this->getShippingPrice($product['id_product'], $id_product_attribute, $carrier_inter, $carrier_zone_inter);
			}

			/*
			 * Images
			 */
			$images = $this->getProductImages($product);
			foreach ($images as $key => $image)
			{
				if (is_object($link))
				{
					$img_url = $link->getImageLink($product['link_rewrite'], (int)$product['id_product'].'-'.(int)$image['id_image'], Gateway::getConfig('IMAGE_TYPE_NAME'));
					$products_temp[$indice]['Image'.($key + 1)] = 'http://'.str_replace('http://', '', $img_url);
				}
			}

			/*
			 * Categorie.
			 */
			$category_default = new Category((int)$product['id_category_default'], (int)$context->cookie->id_lang);
			$products_temp[$indice]['ArrayOfSpecificFields'] = array();
			$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
				'Name' => 'categorie',
				'Value' => $category_default->name
			);

			/*
			 * Product informations
			 */
			$special_fields = array('description_with_HTML', 'description_without_HTML');
			$selected_product_fields = explode('¤', Gateway::getConfig('SYNCHRO_PRODUCT_OTHER_FIELDS_P'));
			$t_select = array();
			foreach ($selected_product_fields as $row)
				if (!empty($row) && !in_array($row, $special_fields))
					$t_select[] = 'p.`'.$row.'`';
			$selected_product_lang_fields = explode('¤', Gateway::getConfig('SYNCHRO_PRODUCT_OTHER_FIELDS_PL'));
			foreach ($selected_product_lang_fields as $row)
				if (!empty($row) && !in_array($row, $special_fields))
					$t_select[] = 'pl.`'.$row.'`';

			$sql = 'SELECT
				p.id_product, IFNULL(pa.id_product_attribute, 0) as id_product_attribute, pl.id_lang,
				IFNULL(CONCAT(pl.name, " ", GROUP_CONCAT(DISTINCT al.name SEPARATOR " ")), pl.name) as name,
				pl.description
				'.(!empty($t_select) ? ','.implode(',', $t_select) : '').'
				FROM '._DB_PREFIX_.'product p
				LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON pa.id_product = p.id_product
				INNER JOIN '._DB_PREFIX_.'product_lang pl ON pl.id_product = p.id_product
				LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac ON pac.id_product_attribute = pa.id_product_attribute
				LEFT JOIN '._DB_PREFIX_.'attribute atr ON atr.id_attribute = pac.id_attribute
				LEFT JOIN '._DB_PREFIX_.'attribute_lang al ON al.id_attribute = atr.id_attribute
				LEFT JOIN '._DB_PREFIX_.'attribute_group_lang agl ON agl.id_attribute_group = atr.id_attribute_group
				WHERE p.id_product = '.(int)$product['id_product'].' AND IFNULL(pa.id_product_attribute, 0) = '.(int)$product['id_product_attribute'].'
					AND pl.id_lang IN ('.implode(',', $t_languages).')
				GROUP BY p.id_product, pa.id_product_attribute, pl.id_lang';
			$results = Db::getInstance()->ExecuteS($sql);

			if ($results)
			{
				$matching_fields = Gateway::getMatchingProductFields();
				$lang_fields = array_merge(array('name', 'description'), $selected_product_lang_fields);
				foreach ($results as $row)
				{
					/* @NewQuest SF : on met le nom du produit sans attribute comme nom de déclinaison. */
					if ((int)$row['id_product_attribute'])
						$row['name'] = $product['name'];

					$index_lang = $translate_to_neteven[$row['id_lang']];
					foreach ($lang_fields as $lang_field)
						if (in_array($lang_field, $special_fields))
							switch ($lang_field)
							{

								case 'description_with_HTML':
									$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
										'Name' => (isset($matching_fields[$lang_field]) ? $matching_fields[$lang_field] : $lang_field),
										'Value' => '<![CDATA['.$row['description'].']]>',
										'lang' => $index_lang
									);
									break;

								case 'description_without_HTML':
									$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
										'Name' => (isset($matching_fields[$lang_field]) ? $matching_fields[$lang_field] : $lang_field),
										'Value' => strip_tags($row['description']),
										'lang' => $index_lang
									);
									break;
							}
						else
						{
							$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
								'Name' => (isset($matching_fields[$lang_field]) ? $matching_fields[$lang_field] : $lang_field),
								'Value' => strip_tags($row[$lang_field]),
								'lang' => $index_lang
							);
						}

					// Coment field special process.
					$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
						'Name' => 'comment',
						'Value' => Gateway::getConfig('COMMENT_LANG_'.$row['id_lang']),
						'lang' => $index_lang
					);
				}

				foreach ($selected_product_fields as $field)
					if (!empty($field))
						$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
							'Name' => $field,
							'Value' => strip_tags($results[0][$field])
						);
			}

			/*
			 * Ajout des Tags
			 */
			foreach ($t_languages as $id_lang)
			{
				$sql = 'SELECT t.name
						FROM '._DB_PREFIX_.'product_tag pt
						INNER JOIN '._DB_PREFIX_.'tag t ON (pt.id_tag = t.id_tag AND t.id_lang = '.(int)($id_lang).')
						WHERE pt.id_product = '.(int)($product['id_product']);
				$t_tags_bdd = Db::getInstance()->ExecuteS($sql);

				if ($t_tags_bdd && count($t_tags_bdd) > 0)
				{
					$t_tags_final = array();
					foreach ($t_tags_bdd as $t_tag_bdd)
						$t_tags_final[] = $t_tag_bdd['name'];

					$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
						'Name' => 'Keywords',
						'Value' => implode(',', $t_tags_final),
						'lang' => $translate_to_neteven[$id_lang]
					);
				}
			}

			/*
			 * Features NEW (mise en cache des feature par produit)
			 */
			if (!isset(self::$cache_feature_other_fields[$product['id_product']]))
			{
				$selected_features = explode('¤', Gateway::getConfig('SYNCHRO_PRODUCT_OTHER_FIELDS_F'));
				$results = Db::getInstance()->Executes('SELECT fp.id_feature_value, fl.id_feature, fvl.id_lang,
					fl.name as name,
					fvl.value as value
					FROM '._DB_PREFIX_.'feature_product fp
					LEFT JOIN '._DB_PREFIX_.'feature_lang fl ON fl.id_feature = fp.id_feature AND fl.id_lang IN ('.implode(',', $t_languages).')
					LEFT JOIN '._DB_PREFIX_.'feature_value_lang fvl ON fvl.id_feature_value = fp.id_feature_value AND fvl.id_lang IN ('.implode(',', $t_languages).')
					WHERE fp.id_product = '.(int)$product['id_product'].' AND fp.id_feature IN ('.implode(',', $selected_features).')
					GROUP BY fp.id_product, fl.id_feature, fvl.id_lang');
				self::$cache_feature_other_fields[$product['id_product']] = $results;
			}
			else
				$results = self::$cache_feature_other_fields[$product['id_product']];

			if ($results)
				foreach ($results as $row)
				{
					$index_lang = $translate_to_neteven[$row['id_lang']];
					$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
						'Name' => $row['name'],
						'Value' => strip_tags($row['value']),
						'lang' => $index_lang
					);

				}

			/*
			 * Attribute NEW
			 */
			if ((int)$product['id_product_attribute'] > 0)
			{
				$selected_attributes = explode('¤', Gateway::getConfig('SYNCHRO_PRODUCT_OTHER_FIELDS_A'));
				$results = Db::getInstance()->Executes('
					SELECT
						pac.id_attribute, agl.id_lang, agl.name as name, al.name as value
					FROM
						`'._DB_PREFIX_.'product_attribute_combination` pac
					LEFT JOIN
						`'._DB_PREFIX_.'attribute` a ON pac.id_attribute = a.id_attribute
					LEFT JOIN
						`'._DB_PREFIX_.'attribute_group_lang` agl ON agl.id_attribute_group = a.id_attribute_group AND agl.id_lang
					LEFT JOIN
						`'._DB_PREFIX_.'attribute_lang` al ON al.id_attribute = a.id_attribute AND al.id_lang = agl.id_lang
					WHERE
						pac.`id_product_attribute` = '.(int)$product['id_product_attribute'].' AND agl.id_lang IN ('.implode(',', $t_languages).')
					GROUP BY
						pac.id_attribute, agl.id_lang
				');
				if ($results)
					foreach ($results as $row)
					{
						$index_lang = $translate_to_neteven[$row['id_lang']];
						$products_temp[$indice]['ArrayOfSpecificFields'][] = array(
							'Name' => $row['name'],
							'Value' => strip_tags($row['value']),
							'lang' => $index_lang
						);

					}
			}

		}

		return $products_temp;
	}

	public function getProductName($id_product, $id_product_attribute = null, $id_lang = null)
	{
		// use the lang in the context if $id_lang is not defined
		if (!$id_lang)
			$id_lang = (int)Context::getContext()->language->id;

		// creates the query object
		$query = new DbQuery();

		// selects different names, if it is a combination
		if ($id_product_attribute)
			//			$query->select('IFNULL(CONCAT(pl.name, \' : \', GROUP_CONCAT(DISTINCT agl.`name`, \' \', al.name SEPARATOR \', \')),pl.name) as name');
			$query->select('IFNULL(CONCAT(pl.name, \' : \', GROUP_CONCAT(DISTINCT al.name SEPARATOR \' \')),pl.name) as name');
		else
			$query->select('DISTINCT pl.name as name');

		// adds joins & where clauses for combinations
		if ($id_product_attribute)
		{
			$query->from('product_attribute', 'pa');
			$query->join(Shop::addSqlAssociation('product_attribute', 'pa'));
			$query->innerJoin('product_lang', 'pl', 'pl.id_product = pa.id_product AND pl.id_lang = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl'));
			$query->leftJoin('product_attribute_combination', 'pac', 'pac.id_product_attribute = pa.id_product_attribute');
			$query->leftJoin('attribute', 'atr', 'atr.id_attribute = pac.id_attribute');
			$query->leftJoin('attribute_lang', 'al', 'al.id_attribute = atr.id_attribute AND al.id_lang = '.(int)$id_lang);
			$query->leftJoin('attribute_group_lang', 'agl', 'agl.id_attribute_group = atr.id_attribute_group AND agl.id_lang = '.(int)$id_lang);
			$query->where('pa.id_product = '.(int)$id_product.' AND pa.id_product_attribute = '.(int)$id_product_attribute);
		}
		else // or just adds a 'where' clause for a simple product
		{
			$query->from('product_lang', 'pl');
			$query->where('pl.id_product = '.(int)$id_product);
			$query->where('pl.id_lang = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl'));
		}

		return Db::getInstance()->getValue($query);
	}

	public function getProductCategories($product)
	{
		$context = Context::getContext();
		$category = $category_default = new Category((int)$product['id_category_default'], (int)$context->cookie->id_lang);
		$categories = array();
		$categories[] = $category->name;
		$security = 0;
		if ($category->id_parent != 1)
			while ($security < 200 && $category->id_parent != 1)
			{
				$category = new Category((int)$category->id_parent, (int)$context->cookie->id_lang);
				if (!empty($category->name))
					$categories[] = $category->name;

				$security++;
			}

		array_reverse($categories);

		return $categories;
	}

	public function getProductImages($product)
	{
		$images = Db::getInstance()->ExecuteS('
			SELECT `id_image`, `cover`
			FROM `'._DB_PREFIX_.'image`
			WHERE `id_product` = '.(int)$product['id_product'].'
			ORDER BY `cover` DESC, `position` ASC
			LIMIT 6
		');

		if (!$product['id_product_attribute'])
			return $images;

		$images_attribute = Db::getInstance()->ExecuteS('
			SELECT i.`id_image`, i.`cover`
			FROM `'._DB_PREFIX_.'product_attribute_image` pai
			INNER JOIN `'._DB_PREFIX_.'image` i USING(id_image)
			WHERE i.`id_product` = '.(int)$product['id_product'].'
			AND pai.`id_product_attribute` = '.(int)$product['id_product_attribute'].'
			ORDER BY i.`cover` DESC, i.`position` ASC
			LIMIT 6
		');

		if (!empty($images_attribute))
			return $images_attribute;

		return $images;
	}

	/**
	 * Envoie des produits a NetEven.
	 *
	 * @param $t_retour
	 * @return mixed
	 */
	private function addProductInNetEven($neteven_products)
	{
		if (count($neteven_products) == 0)
		{
			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('No product to send !'));

			return;
		}

		try
		{
			Toolbox::addLogLine(self::getL('Number of product send to NetEven').' '.count($neteven_products));
			$params = array('items' => $neteven_products);

			$response = $this->client->PostItems($params);
			$itemsStatus = '';

			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('Sends data to NetEven'));

		}
		catch (Exception $e)
		{
			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('Failed to send data to Neteven'));

			$erreur = '<pre>Last request:\n'.$this->client->__getLastRequest().'</pre>\n';
			Toolbox::manageError($e, 'add product nombre => '.count($neteven_products).' '.$erreur);
			$response = '';
			$itemsStatus = '';
		}

		if ($this->getValue('send_request_to_mail'))
			$this->sendDebugMail($this->getValue('mail_list_alert'), self::getL('Debug - Control request').' addProductInNetEven', $this->client->__getLastRequest(), true);

		if ($response != '')
		{
			if (!empty($response->PostItemsResult) && !empty($response->PostItemsResult->InventoryItemStatusResponse) && is_array($response->PostItemsResult->InventoryItemStatusResponse))
				foreach ($response->PostItemsResult->InventoryItemStatusResponse as $rep)
				{
					Toolbox::addLogLine($rep->ItemCode.' '.$rep->StatusResponse);
					if ($this->getValue('debug'))
						Toolbox::displayDebugMessage(self::getL('Add product').' : '.$rep->ItemCode.' '.$rep->StatusResponse);

				}
			else
			{
				Toolbox::addLogLine($response->PostItemsResult->InventoryItemStatusResponse->ItemCode.' '.$response->PostItemsResult->InventoryItemStatusResponse->StatusResponse);
				if ($this->getValue('debug'))
					Toolbox::displayDebugMessage(self::getL('Add product').' : '.$response->PostItemsResult->InventoryItemStatusResponse->ItemCode.' '.$response->PostItemsResult->InventoryItemStatusResponse->StatusResponse);

			}

			// Reset change config vars.
			Gateway::updateConfig('SYNCHRO_PRODUCT_CHANGE_ACTIVE', 0);
			Gateway::updateConfig('SYNCHRO_PRODUCT_CHANGE_OOS', 0);

			// Reset inactive product to update.
			if (!empty($this->ids_inactive))
				Gateway::deleteInactiveProduct($this->ids_inactive);
		}

		Toolbox::writeLog();
		Configuration::updateValue('neteven_date_export_product', date('Y-m-d H:i:s'));
	}

	/**
	 * Récupération du prix de shipping pour un produit id
	 *
	 * @param $shipping
	 * @return float
	 */
	public function getShippingPrice($product_id, $attribute_id, $id_carrier = 0, $id_zone = 0)
	{
		$product = new Product($product_id);
		$shipping = 0;
		$carrier = new Carrier((int)$id_carrier);

		if ($id_zone == 0)
		{
			$defaultCountry = new Country(Configuration::get('PS_COUNTRY_DEFAULT'), Configuration::get('PS_LANG_DEFAULT'));
			$id_zone = (int)$defaultCountry->id_zone;
		}

		$carrierTax = Tax::getCarrierTaxRate((int)$carrier->id);

		$free_weight = Configuration::get('PS_SHIPPING_FREE_WEIGHT');
		$shipping_handling = Configuration::get('PS_SHIPPING_HANDLING');

		if ($product->getPrice(true, $attribute_id, 2, null, false, true, 1) >= (float)$free_weight && (float)$free_weight > 0)
			$shipping = 0;
		elseif (isset($free_weight) && $product->weight >= (float)$free_weight && (float)$free_weight > 0)
			$shipping = 0;
		else
		{
			if (isset($shipping_handling) && $carrier->shipping_handling)
				$shipping = (float)$shipping_handling;

			if ($carrier->getShippingMethod() == Carrier::SHIPPING_METHOD_WEIGHT)
				$shipping += $carrier->getDeliveryPriceByWeight($product->weight, $id_zone);
			else
				$shipping += $carrier->getDeliveryPriceByPrice($product->getPrice(true, $attribute_id, 2, null, false, true, 1), $id_zone);

			$shipping *= 1 + ($carrierTax / 100);
			$shipping = (float)Tools::ps_round((float)$shipping, 2);
		}

		unset($product);

		return $shipping;
	}

	public function viewProductInNetEven()
	{
		try
		{
			$response = $this->client->GetItems();
			$items = $response->items->InventoryItem;
		}
		catch (Exception $e)
		{
			$response = '';
			$items = '';
		}

		Tools::p($items);
	}

	public function updateStock($is_display = true)
	{
		$indice = 0;
		$products = $this->getAllProductToExportStock(array(), $indice);

		if ($this->getValue('debug'))
			Toolbox::displayDebugMessage(self::getL('Quantity of recovered product').' : '.count($products));

		if ($this->getValue('debug'))
			Toolbox::displayDebugMessage(self::getL('Quantity of recovered product after removing products without EAN code').' : '.count($products));

		$security = 0;
		$control_while = true;

		while ($control_while)
		{

			$neteven_products = $this->getPropertiesStockForNetEven($products, $is_display);

			if ($is_display)
				echo '<pre>'.print_r($neteven_products, true).'</pre>';

			if (!$is_display)
				$this->addProductInNetEven($neteven_products);

			$indice++;
			$products_base = $this->getAllProductToExportStock(array(), $indice);

			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('Quantity of recovered product').' : '.count($products_base));

			$products = $products_base;

			if ($this->getValue('debug'))
				Toolbox::displayDebugMessage(self::getL('Quantity of recovered product after removing products without EAN code').' : '.count($products));

			$security++;
			if ($security > 1000)
				$control_while = false;

			if ((is_array($products_base) && count($products_base) == 0) || !is_array($products_base))
				$control_while = false;

			// Ajout pour le flux en ajax : On incrémente le lot en cours.
			self::$synchro_product_lot++;
		}
	}

	public function getAllProductToExportStock($products_exlusion = array(), $indice = 0)
	{
		$context = Context::getContext();

		if ($this->getValue('debug'))
			$neteven_date_export_product = '';
		else
			$neteven_date_export_product = Configuration::get('neteven_date_export_product');

		$separator = $this->getValue('separator');

		$id_lang = isset($context->cookie->id_lang) ? (int)$context->cookie->id_lang : (int)Configuration::get('PS_LANG_DEFAULT');
		$sql = 'SELECT SQL_CALC_FOUND_ROWS
			p.`id_product`,
			IFNULL(pa.id_product_attribute, 0) as id_product_attribute,
			p.`ean13`,
			pa.`ean13` as ean13_declinaison,
			p.`reference` as product_reference,
			pa.`reference` as product_attribute_reference,
			p.`wholesale_price`
			'.((self::$type_sku != 'reference') ? ',(SELECT CONCAT(\'D\', pa2.`id_product_attribute`) FROM `'._DB_PREFIX_.'product_attribute` pa2 WHERE pa2.`id_product` = p.`id_product` AND `default_on` = 1 LIMIT 1) as declinaison_default' : '').'
				'.((self::$type_sku == 'reference') ? ',(SELECT pa2.`reference` FROM `'._DB_PREFIX_.'product_attribute` pa2 WHERE pa2.`id_product` = p.`id_product` AND `default_on` = 1 LIMIT 1) as declinaison_default_ref' : '').'
		FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = p.`id_product`)
		WHERE 1 '.((is_array($products_exlusion) && count($products_exlusion) > 0) ? ' AND (p.`reference` NOT IN ('.implode(',', pSQL($products_exlusion)).') AND pa.`reference` NOT IN ('.implode(',', pSQL($products_exlusion)).'))' : '');

		if (self::$stock_export_only_active)
			$sql .= ' AND p.`active` = 1 AND p.`available_for_order` = 1';

		$sql .= ' GROUP BY p.`id_product`, pa.`id_product_attribute`
		LIMIT '.($indice * 100).', 100
		';

		if ($this->getValue('debug'))
			Toolbox::displayDebugMessage($sql);

		$products = Db::getInstance()->ExecuteS($sql);

		// Ajout pour le flux en ajax.
		if (empty(self::$synchro_product_lot_total))
		{
			self::$synchro_product_total = Db::getInstance()->getValue('SELECT FOUND_ROWS() AS NbRows');
			self::$synchro_product_lot_total = self::$synchro_product_total / 100 + 1;
			self::$synchro_product_lot = 1;
		}

		Toolbox::addLogLine(self::getL('Product to update or create').' '.count($products));
		Toolbox::writeLog();

		return $products;
	}

	/**
	 * Formatage des informations produit pour NetEven.
	 *
	 * @param $t_product
	 * @param bool $display
	 * @return array
	 */
	private function getPropertiesStockForNetEven($products, $display = false)
	{
		if (!count($products) || !$products)
			return false;

		$context = Context::getContext();

		$link = new Link();

		$products_temp = array();

		$compteur_product_no_ean13 = 0;
		$compteur_product_no_ref = 0;
		foreach ($products as $product)
		{
			// Ajout pour le flux en ajax.
			self::$synchro_product_current++;
			Gateway::setStepAjaxCron(2, 'Processed : '.(int)ceil((self::$synchro_product_current * 100) / self::$synchro_product_total).' % - lot en cours : '.self::$synchro_product_lot.' / '.(int)self::$synchro_product_lot_total, Tools::getValue('uniqkey'));

			/*
			 * Reference
			 */
			$product_reference = 'P'.$product['id_product'];
			if (!empty($product['id_product_attribute']))
				$product_reference = 'D'.$product['id_product_attribute'];

			if (self::$type_sku == 'reference')
			{
				$product_reference = $product['product_reference'];
				if (!empty($product['id_product_attribute']))
					$product_reference = $product['product_attribute_reference'];
			}

			/*
			 * Ean13
			 */
			$ean_ps = !empty($product['ean13_declinaison']) ? $product['ean13_declinaison'] : $product['ean13'];
			$codeEan = '';
			if (!empty($ean_ps))
				$codeEan = sprintf('%013s', $ean_ps);

			/*
			 * Product attribute
			 */
			$id_product_attribute = null;
			if (!empty($product['id_product_attribute']))
				$id_product_attribute = (int)$product['id_product_attribute'];

			/*
			 * Quantity
			 */
			$quantity = Product::getQuantity((int)$product['id_product'], !empty($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);

			if (!self::$stock_export_oos && (int)$quantity <= 0)
				continue;

			$indice = count($products_temp);

			$products_temp[$indice] = array(
				'SKU' => $product_reference,
				'Quantity' => $quantity,
			);

			/*
			 * Prices
			 */
			list($t_values, $fields) = Gateway::getFieldsMatchTab();
			foreach ($fields as $name => $field)
			{
				if ($field['values_group'] == 'prices')
				{
					$config = Gateway::getConfig('SYNCHRO_PRODUCT_MATCH_'.$name);
					$product['id_country'] = Gateway::getConfig('SYNCHRO_PRODUCT_MATCH_COUNTRY_'.$name);
					if (!empty($config))
					{
						$type = $t_values[$field['values_group']][$config]['type'];
						if ($type == 'function')
							$products_temp[$indice][$name] = array(
								'_' => $this->{$t_values[$field['values_group']][$config]['callback']}($product),
								'currency_id' => $this->getCurrencyIsoForProduct($product['id_country'])
							);
						else
						{
							switch ($config)
							{
								case 'ean13' :
									$content = $codeEan;
									break;
								case 'upc' :
									$content = '';
									break;
								case 'reference' :
									$content = $product_reference;
									break;
								case 'wholesale_price':
									$content = $product['wholesale_price'];
									break;
								case 'height':
									$content = $product['height'];
									break;
								case 'width':
									$content = $product['width'];
									break;
								case 'depth':
									$content = $product['depth'];
									break;
							}

							$products_temp[$indice][$name] = $content;
						}
					}
				}
			}
		}

		return $products_temp;
	}
}