<?php

class JW_GenerateSamples_Model_Observer
{
    /**
     * Creates a sample product
     * @param Varien_Event_Observer $observer
     * @return JW_GenerateSamples_Model_Observer
     */
    public function generateSample($observer)
    {
    	/**
         * @var $product Mage_Catalog_Model_Product
         */
        $parentProduct = $observer->getEvent()->getProduct();
        
        if ($parentProduct->getAllowSamples()){
            
        	$parentSku = $parentProduct->getSku();
        	
        	$newSku = $parentSku.'-SAMPLEAAAAA';
            $helper = Mage::helper('jw_generatesamples');

            $sample_product_id = Mage::getModel("catalog/product")->getIdBySku( $newSku );
  
            if ( !is_numeric( $sample_product_id ) ) {	
        		try {
        			$helper->createNewSample($newSku, $parentProduct);
						
				} catch(Exception $e) {
					Mage::log('Error: '.$e->getMessage(), 3, 'jw_generateSamples.log');	
				}
			}
        }
        return $this;
    }


	public function process()
	{
        Mage::log('Begin Generate Sample Product INDEX', 6, 'jw_generateSamples.log');
        $ct = 0;

        $helper = Mage::helper('jw_generatesamples');
        $collectionAllowSamples = $helper->getSamples();

        foreach ($collectionAllowSamples as $allowSample) {
            $sampleSku = $allowSample['sample_sku'];
            $sample_product_id = Mage::getModel("catalog/product")->getIdBySku( $sampleSku );
            if ( !is_numeric( $sample_product_id ) ) {
                $parentProduct = Mage::getModel('catalog/product')->load($allowSample['entity_id']);
                try {
                    $helper->createNewSample($sampleSku, $parentProduct);
                    $ct++;
                } catch(Exception $e){
                    Mage::log('Error: '.$e->getMessage(), 3, 'jw_generateSamples.log');
                }
            }
        }
        Mage::log('Complete Generate Sample Product INDEX; '.$ct.' samples created. ', 6, 'jw_generateSamples.log');

        return;
	}
}