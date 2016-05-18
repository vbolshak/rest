<?php
/**
 * Created by PhpStorm.
 * User: serf
 * Date: 17.05.16
 * Time: 16:41
 */

class Serfcompany_Amazingcart_Model_Product extends Mage_Catalog_Model_Product{
    
    
    public function getProduct($id){

        return $this->jsonSetup($id);
           
    }



    public function jsonSetup($id)
    {


        $product = Mage::getModel('catalog/product')->load($id);

        $array = array
        (

            "product_ID"=>$product->getData('entity_id'),
            "is_downloadble"=>null,
            "is_virtual"=>$product->isVirtual(),
            "is_purchasable"=>$product->isSalable(),
            "is_featured"=>null,
            "visibility"=>$product->getData('visibility'),
            "general"=>array
            (
                "title"=>$product->getData('name'),
                "link"=>Mage::getBaseUrl().'/'.$product->getData('url_path'),
                "content"=>array
                (
                    "full_html"=>$product->getData('short_description'),
                    "excepts"=>null
                ),
                "SKU"=>$product->getData('SKU'),
                "product_type"=>$product->getData('type_id'),
                "if_external"=>array
                (
                    "product_url"=>null,
                    "button_name"=>null
                ),
                "pricing"=>array
                (
                    "is_on_sale"=>null,
                    "currency"=>null,
                    "regular_price"=>$product->getData('price'),
                    "sale_price"=>null,
                    "sale_start"=>array(
                        "unixtime"=>null,
                        "day"=>null,
                        "month"=>null,
                        "year"=>null,
                        "day_name"=>null,
                        "fulldate"=>null,
                    ),
                    "sale_end"=>array(
                        "unixtime"=>null,
                        "day"=>null,
                        "month"=>null,
                        "year"=>null,
                        "day_name"=>null,
                        "fulldate"=>null,
                    ),
                ),
                "tax_status"=>null,
                "tax_class"=>null
            ),
            "inventory"=>array
            (
                "manage_stock"=>null,
                "quantity"=>$product->getData('stock_item')->getData('qty'),
                "stock_status"=>$product->getData('stock_item')->getData('is_in_stock'),
                "allow_backorder"=>null,
                "allow_backorder_require_notification"=>null,
                "sold_individually"=>null
            ),
            "shipping"=>array
            (
                "weight"=>array
                (
                    "has_weight"=>null,
                    "unit"=>null,
                    "value"=>null
                ),
                "dimension"=>array
                (
                    "has_dimension"=>null,
                    "unit"=>null,
                    "value_l"=>null,
                    "value_w"=>null,
                    "value_h"=>null
                ),
                "shipping_class"=>array
                (
                    "class_name"=>null,
                    "class_id"=>null
                ),
            ),
            "linked_products"=>array
            (
                "upsells"=>null,
                "cross_sale"=>null,
                "grouped"=>null
            ),
            "attributes"=>array
            (
                "has_attributes"=>null,
                "attributes"=>null
            ),
            "advanced"=>array
            (
                "purchase_note"=>null,
                "menu_order"=>null,
                "comment_status"=>null
            ),
            "ratings"=>array
            (
                "average_rating"=>null,
                "rating_count"=>null
            ),
            "if_variants"=>null,
            "if_group"=>null,
            "product_gallery"=>array
            (
                "featured_images"=>$product->getData('image'),
                "other_images"=>$product->getData('media_gallery')
            ),
            "categories" =>null

        );

        return $array;


    }


    public function loginUser($email, $password)
    {
        
        require_once("app/Mage.php");
        umask(0);
        ob_start();
        session_start();
        Mage::app('default');
        Mage::getSingleton("core/session", array("name" => "frontend"));

        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
        $customer = Mage::getModel("customer/customer");
        $customer->website_id = $websiteId;
        $customer->setStore($store);
        try {
            $customer->loadByEmail($email);
            $session = Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
            $session->login($email, $password);
            $customerBillingAdress = $customer->getPrimaryBillingAddress();
            $array = array(
                "status"=>0,
                "reason"=>"Successful Log",
                "user"=>array(
                    "ID"=>$customer->getData('entity_id'),
                    "user_login"=>$customer->getData('email'),
                    "avatar"=>null,
                    "first_name"=>$customer->getData('firstname'),
                    "last_name"=>$customer->getData('lastname'),
                    "email"=>$customer->getData('email'),
                    "user_nicename"=>null,
                    "user_nickname"=>null,
                    "user_status"=>$customer->getData('is_active'),
                    "order_count"=>$this->getCountOrderByUser($customer->getData('entity_id')),
                    "credit_card_management_aut_net"=>null,
                    "user_registered"=>array(
                        "db_format"=>$customer->getData('created_at'),
                        "unixtime"=>$customer->getData('created_at'),
                        "servertime"=>Mage::app()->getLocale()->storeTimeStamp(Mage::app()->getStore()),
                        "ago"=>$this->null,
                    ),
                    "billing_address"=>array(
                        "billing_first_name"=>$customerBillingAdress->getData('firstname'),
                        "billing_last_name"=>$customerBillingAdress->getData('lastname'),
                        "billing_company"=>$customerBillingAdress->getData('company'),
                        "billing_address_1"=>explode(PHP_EOL , $customerBillingAdress->getData('street'))[0],
                        "billing_address_2"=>explode(PHP_EOL , $customerBillingAdress->getData('street'))[1],
                        "billing_city"=>$customerBillingAdress->getData('city'),
                        "billing_postcode"=>$customerBillingAdress->getData('postcode'),
                        "billing_state"=>$customerBillingAdress->getData('region'),
                        "billing_state_code"=>null,
                        "billing_has_state"=>null,
                        "billing_country"=>Mage::app()->getLocale()->getCountryTranslation($customerBillingAdress->getData('country_id')),
                        "billing_country_code"=>$customerBillingAdress->getData('country_id'),
                        "billing_phone"=>$customerBillingAdress->getData('telephone'),
                        "billing_email"=>$customer->getData('email')
                    ),
                   // "shipping_address"=>array(
                      //  "shipping_first_name"=>$user->shipping_first_name,
                    //    "shipping_last_name"=>$user->shipping_last_name,
                     //   "shipping_company"=>$user->shipping_company,
                     //   "shipping_address_1"=>$user->shipping_address_1,
                     //   "shipping_address_2"=>$user->shipping_address_2,
                     //   "shipping_city"=>$user->shipping_city,
                     //   "shipping_postcode"=>$user->shipping_postcode,
                     //   "shipping_state"=>$state_shipping,
                     //   "shipping_state_code"=>$statShippingeCode,
                      //  "shipping_has_state"=>$stateShippingAvailable,
                      //  "shipping_country"=>$this->countryCodeToNameConverter($user->shipping_country),
                     //   "shipping_country_code"=>$user->shipping_country
                   // )
                )
            );
            return $array;
        } catch (Exception $e) {
           return $array = array("status"=>1,"reason"=>"Username/email/password is wrong");;
        }
//
//
    }

    public function getCountOrderByUser($userId)
    {
        //filtering the customers by email address
        $orderCollection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', $userId)
            ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
            ->setOrder('created_at', 'desc')
        ;
        return $orderCollection->getSize();
        
    }
}
