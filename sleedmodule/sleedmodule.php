<?php

// check for prestaShop version and if it doesn't exists, stop module from loading.
if (!defined('_PS_VERSION_')) {
    exit;
}

// module main class
class SleedModule extends Module
{
    public function __construct()
    {
        $this->name = 'sleedmodule';
        $this->tab = 'front_office_features';
        $this->version = '9.0.0';
        $this->author = 'Dimitrios Verakis';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Sleed Module');
        $this->description = $this->l('Extra functionality for our eshop.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        // altering DB-PREFIX_product_lang table to add our 3 new fields
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product_lang ADD `custom_field_1` varchar(32)');
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product_lang ADD `custom_field_2` varchar(32)');
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product_lang ADD `custom_field_3` varchar(32)');

        // register the hooks we will need
        if (
            !parent::install() ||
            !$this->registerHook('actionProductUpdate') ||
            !$this->registerHook('footerProduct') ||
            !$this->registerHook('displayAdminProductsExtra') ||
            !Configuration::updateValue('MYMODULE_NAME', 'my friend')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (
            !parent::uninstall() ||
            !Configuration::deleteByName('MYMODULE_NAME')
        ) {
            return false;
        }

        // altering DB-PREFIX_product_lang table to remove our 3 fields
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product_lang DROP `custom_field_1`');
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product_lang DROP `custom_field_2`');
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'product_lang DROP `custom_field_3`');

        return true;
    }


    // get the necessary fields from the database for the specific product.
    // prepare the variables for smarty and send them to sleedmodule.tpl
    // for adding our new tab on product edit page with the three inputs and buttons
    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = (int) Tools::getValue('id_product');

        $query1 = "SELECT custom_field_1 FROM `" . _DB_PREFIX_ . "product_lang` WHERE id_product='$id_product'"; //end of the query
        $value1 = Db::getInstance()->getValue($query1);

        $query2 = "SELECT custom_field_2 FROM `" . _DB_PREFIX_ . "product_lang` WHERE id_product='$id_product'"; //end of the query
        $value2 = Db::getInstance()->getValue($query2);

        $query3 = "SELECT custom_field_3 FROM `" . _DB_PREFIX_ . "product_lang` WHERE id_product='$id_product'"; //end of the query
        $value3 = Db::getInstance()->getValue($query3);

        // $value1 = $value1 ? $value1 : 'No Input yet';
        // $value2 = $value2 ? $value2 : 'NoInputyet';
        // $value3 = $value3 ? $value3 : 'No Input yet';

        $this->context->smarty->assign(array('value1' => $value1, 'value2' => $value2, 'value3' => $value3));
        return $this->display(__FILE__, 'sleedmodule.tpl');
    }


    // when the user updates the product, by changing our fields and clicks save
    // we get the value of the input fields and updating our database.
    public function hookActionProductUpdate($params)
    {
        $id_product = (int) Tools::getValue('id_product');

        $sleed_item1 = Tools::getValue('sleed_item1');
        $sleed_item2  = Tools::getValue('sleed_item2');
        $sleed_item3  = Tools::getValue('sleed_item3');

        $query = "UPDATE `" . _DB_PREFIX_ . "product_lang` SET custom_field_1='$sleed_item1',custom_field_2='$sleed_item2',custom_field_3='$sleed_item3'  WHERE id_product='$id_product'"; //end of the query
        Db::getInstance()->Execute($query);
    }


    // this hook is responsible for showing our data in any product page
    // quering the database for our custom fields and sending them in sleedmoduleproduct.tpl
    // so they get displayed under the product
    public function hookDisplayFooterProduct($params)
    {
        $id_product = (int) Tools::getValue('id_product');

        $query1 = "SELECT custom_field_1 FROM `" . _DB_PREFIX_ . "product_lang` WHERE id_product='$id_product'"; //end of the query
        $value1 = Db::getInstance()->getValue($query1);

        $query2 = "SELECT custom_field_2 FROM `" . _DB_PREFIX_ . "product_lang` WHERE id_product='$id_product'"; //end of the query
        $value2 = Db::getInstance()->getValue($query2);

        $query3 = "SELECT custom_field_3 FROM `" . _DB_PREFIX_ . "product_lang` WHERE id_product='$id_product'"; //end of the query
        $value3 = Db::getInstance()->getValue($query3);


        $this->context->smarty->assign(array('value1' => $value1, 'value2' => $value2, 'value3' => $value3));

        return $this->display(__FILE__, 'sleedmoduleproduct.tpl');
    }
}
