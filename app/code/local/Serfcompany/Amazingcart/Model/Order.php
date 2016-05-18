<?php
/**
 * Created by PhpStorm.
 * User: serf
 * Date: 18.05.16
 * Time: 13:39
 */

class Serfcompany_AmazingCart_Model_Order extends Mage_Core_Model_Abstract {


    protected $_quote;
    
    public function initialize( $customer, $productsIds ){

        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
        // Start New Sales Order Quote
        $this->_quote =  Mage::getModel('sales/quote')->setStoreId($store->getId());
        $quote =  $this->_quote ;

        // Set Sales Order Quote Currency
        $quote->setCurrency(Mage::app()->getStore($store)->getCurrentCurrencyCode());

        // Assign Customer To Sales Order Quote
        $quote->assignCustomer($customer);

        $quote->setSendCconfirmation(1);

        foreach($productsIds as $id) {
            $product = Mage::getModel('catalog/product')->load($id);
            $quote->addProduct($product, new Varien_Object(array('qty' => 2)));
        }
            $billingAddress = $quote->getBillingAddress()->addData(array(
                'customer_address_id' => '',
                'prefix' => '',
                'firstname' => 'john',
                'middlename' => '',
                'lastname' =>'Deo',
                'suffix' => '',
                'company' =>'',
                'street' => array(
                    '0' => 'Noida',
                    '1' => 'Sector 64'
                ),
                'city' => 'Noida',
                'country_id' => 'IN',
                'region' => 'UP',
                'postcode' => '201301',
                'telephone' => '78676789',
                'fax' => 'gghlhu',
                'vat_id' => '',
                'save_in_address_book' => 1
            ));

            // Set Sales Order Shipping Address
            $shippingAddress = $quote->getShippingAddress()->addData(array(
                'customer_address_id' => '',
                'prefix' => '',
                'firstname' => 'john',
                'middlename' => '',
                'lastname' =>'Deo',
                'suffix' => '',
                'company' =>'',
                'street' => array(
                    '0' => 'Noida',
                    '1' => 'Sector 64'
                ),
                'city' => 'Noida',
                'country_id' => 'IN',
                'region' => 'UP',
                'postcode' => '201301',
                'telephone' => '78676789',
                'fax' => 'gghlhu',
                'vat_id' => '',
                'save_in_address_book' => 1
            ));


            // Collect Rates and Set Shipping & Payment Method
            $shippingAddress->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod('flatrate_flatrate')
                ->setPaymentMethod('checkmo');

            // Set Sales Order Payment
            $quote->getPayment()->importData(array('method' => 'checkmo'));

            // Collect Totals & Save Quote
            $quote->collectTotals()->save();
            $items = $quote->getItemsCollection();
            $shippingCost = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingAmount();
            $shippingName = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingDescription();
            $paymentName =  Mage::getSingleton('checkout/session')->getQuote()->getPayment() ->getMethodInstance()->getTitle();
            $content = array();

                foreach ($items as $_item){
                    array_push($content, array(
                        "id" => $_item->getData('product_id'),
                        "product-price" => $_item->getData('price'),
                        "product-price-tax" => $_item->getData('tax_amount'),
                        "total-price" =>  $_item->getData('row_total'),
                        "total-price-tax" => $_item->getData('row_total_incl_tax'),
                        "quantity" => $_item->getData('qty')

                    ));
                }


            return array(
            "cart" => $content,
            "coupon" => array(
                "applied-coupon" => null,
//                    "discount-ammount" => array($woocommerce->cart->coupon_discount_amounts),
                "coupon-array-inserted" => null
            ),
            "has_tax" => null,
            "currency" => $quote->getData('currency'),
            "display-price-during-cart-checkout" => null,
            "cart-subtotal" => $quote->getData('subtotal'),
            "cart-subtotal-ex-tax" => $quote->getData('subtotal'),
            "cart-tax-total" => $quote->getData('grand_total'),
            "shipping-cost" => $shippingCost,
            "shipping-method" => $shippingName,
            "discount" => $quote->getData('subtotal') - $quote->getData('subtotal_with_discount'),
            "grand-total" => (float)$quote->getData('grand_total'),
            "payment-method" => null,
            "shipping_available" => null
        );   
            
              

    }
    
    
    
    public function convertQuoteToOrder($quote){

        // Create Order From Quote
        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();
        $increment_id = $service->getOrder()->getRealOrderId();

    }











}