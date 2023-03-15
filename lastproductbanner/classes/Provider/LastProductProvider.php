<?php
/**
* 2007-2022 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

namespace PrestaShop\Module\LastProductBanner\Provider;

use DbQuery;
use Db;
use Product;

class LastProductProvider
{
    /**
     * LastProductProvider::getLastProductCreatedAndEnabled
     * 
     * Get last product created and enabled for specific shop and lang
     * 
     * @param int $id_shop
     * @param int $id_lang
     * 
     * @return Product|null
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public static function getLastProductCreatedAndEnabled($id_shop, $id_lang)
    {
        if ($id_shop == null || $id_lang == null) {
            return null;
        }

        $id_product = LastProductProvider::getLastProductCreatedAndEnabledId($id_shop);
        
        if ($id_product == null) {
            return null;
        }
        
        $product = new Product($id_product, false, $id_lang, $id_shop);
        $product->price = Product::getPriceStatic($id_product);

        return $product;
    }

    /**
     * LastProductProvider::getLastProductCreatedAndEnabledId
     * 
     * Get product id of last product created and enabled for specific shop and lang
     * 
     * @param int $id_shop
     *
     * @return int|null
     *
     * @throws \PrestaShopDatabaseException
     */
    private static function getLastProductCreatedAndEnabledId($id_shop) {
        if ($id_shop == null) {
            return null;
        }
        
        $query = new DbQuery();
        $query->select('id_product');
        $query->from('product_shop');
        $query->where('id_shop = ' . (int) $id_shop);
        $query->where('active = 1');
        $query->orderBy('date_add DESC');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

}