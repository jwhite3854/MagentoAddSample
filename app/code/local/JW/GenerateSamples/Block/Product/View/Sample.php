<?php

class JW_GenerateSamples_Block_Product_View_Sample extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/product/view/addsample.phtml');
    }
    
    public function getSample()
    {
    	$product = Mage::registry('product');
    	
    	$sample = Mage::getModel('catalog/product')->loadByAttribute('sku',$product->getSku().'-SAMPLE');
    	return $sample;
    }
}