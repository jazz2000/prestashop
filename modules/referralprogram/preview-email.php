<?php
/*
* 2007-2011 PrestaShop 
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
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 11465 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once('../../config/config.inc.php');
require_once('../../init.php');
/*
if (!$cookie->isLogged())
	Tools::redirect('../../authentication.php?back=modules/referralprogram/referralprogram-program.php');
*/
$shop_name = htmlentities(Configuration::get('PS_SHOP_NAME'), NULL, 'utf-8');
$shop_url = Tools::getHttpHost(true, true);
$customer = new Customer((int)($cookie->id_customer));

if (!preg_match("#.*\.html$#Ui", Tools::getValue('mail')) OR !preg_match("#.*\.html$#Ui", Tools::getValue('mail')))
	die(Tools::displayError());
	
$file = file_get_contents(dirname(__FILE__).'/mails/'.strval(preg_replace('#\.{2,}#', '.', Tools::getValue('mail'))));

$file = str_replace('{shop_name}', $shop_name, $file);
$file = str_replace('{shop_url}', $shop_url.__PS_BASE_URI__, $file);
$file = str_replace('{shop_logo}', $shop_url._PS_IMG_.'logo.jpg', $file);
$file = str_replace('{firstname}', $customer->firstname, $file);
$file = str_replace('{lastname}', $customer->lastname, $file);
$file = str_replace('{email}', $customer->email, $file);
$file = str_replace('{firstname_friend}', 'XXXXX', $file);
$file = str_replace('{lastname_friend}', 'xxxxxx', $file);
$file = str_replace('{link}', 'authentication.php?create_account=1', $file);

$discount_type = (int)(Configuration::get('REFERRAL_DISCOUNT_TYPE'));
if ($discount_type == 1)
{
	$file = str_replace('{discount}', Discount::display((float)(Configuration::get('REFERRAL_PERCENTAGE')), $discount_type, new Currency($cookie->id_currency)), $file);
}
else
{
	$file = str_replace('{discount}', Discount::display((float)(Configuration::get('REFERRAL_DISCOUNT_VALUE_' . $cookie->id_currency)), $discount_type, new Currency($cookie->id_currency)), $file);
}

echo $file;