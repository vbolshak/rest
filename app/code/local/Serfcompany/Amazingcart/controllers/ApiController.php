<?php
/**
 * Created by PhpStorm.
 * User: serf
 * Date: 17.05.16
 * Time: 15:59
 */

class Serfcompany_Amazingcart_ApiController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()


    {
        $email = "test@domain.com";
        $password = "admin123";

        print_r (Mage::getModel('amazingcart/product')->loginUser($email, $password));

        //check is user loged in
        if(!Mage::getSingleton('customer/session')->isLoggedIn()){
            $d = 'not loged';
        }else{
            // logged in
            $d = 'loged';
        }
        $sourcOrder = new Varien_Object();

//
        $quota = Mage::getModel('amazingcart/order');
// ; $session ->catuy =
        $products = array('1'=>'905', '2'=>'904' );
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $quota->initialize($customer , $products);
       

      




        if ($this->getRequest()->getParam('type') == 'single-product') {
            // http://magento.loc/amazingcart/api/index/?type=single-product&id=231
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('amazingcart/product')->getProduct($id);
            print_r($model);
        } 
        elseif ($this->getRequest()->getParam('type') == 'user-login') {

            if ($this->getRequest()->getPost('email')) {

                $email = $this->getRequest()->getPost('email');
                $password = $this->getRequest()->getPost('password');
                print_r (Mage::getModel('amazingcart/product')->loginUser($email, $password));


            }
        }
        elseif ($this->getRequest()->getParam('type') == 'cart-api') {
            
            if ($this->getRequest()->getPost('email') && $this->getRequest()->getPost('password')) {

                $email = $this->getRequest()->getPost('email');
                $password = $this->getRequest()->getPost('password');
                Mage::getModel('amazingcart/product')->loginUser($email, $password);
                if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                    die('user isn\t logined');    
                }
                else
                {
                    $cart = Mage::getModel('amazingcart/order');
                    $customer = Mage::getSingleton('customer/session')->getCustomer();
                    $products = json_decode($this->getRequest()->getPost('productsIds'));
                    $cart->initialize($customer , $products );
                   


                    
                    
                }
                
                
               


            }
        }
        

//                die('no action');


    }



}