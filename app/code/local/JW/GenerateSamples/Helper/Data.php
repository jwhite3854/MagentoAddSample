<?php

class JW_GenerateSamples_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getSamples()
    {
        $collectionAllowSamples = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('sku')
            ->addAttributeToFilter('allow_samples', array('eq' => '1'));
     
        return $collectionAllowSamples;
    }

    public function createNewSample($sampleSku,$parentProduct)
	{
		$newProduct = Mage::getModel('catalog/product');
		
		$imagePath = Mage::helper('catalog/image')->init($parentProduct, 'image');
		
		$newProduct
			->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
			->setAttributeSetId($parentProduct->getAttributeSetId()) 
			->setTypeId('simple') 

			->setSku($sampleSku) //SKU
			->setName($parentProduct->getName().' - Sample') //product name
			->setWeight(1)
			->setStatus(1) 
			->setTaxClassId(2)
			->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) //catalog and search visibility
			->setPrice(2) 

			->setMetaTitle($parentProduct->getMetaTitle())
			->setMetaDescription($parentProduct->getMetaDescription())

			->setDescription($parentProduct->getDescription())
			->setShortDescription($parentProduct->getShortDescription())
			
			->setAllowSamples(0)
			->setProductType($parentProduct->getProductType())
			->setSku2($parentProduct->getSku2())

			->setImage($parentProduct->getImage())
			->setSmallImage($parentProduct->getSmallImage())
			->setThumbnail($parentProduct->getThumbnail())
				  
			->setStockData(array(
				'use_config_manage_stock' => 0, //'Use config settings' checkbox
				'manage_stock' => 1, //manage stock
				'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
				'max_sale_qty' => 1, //Maximum Qty Allowed in Shopping Cart
				'is_in_stock' => 1, //Stock Availability
				'qty' => 50 //qty
				)
			);
	
		$newProduct->save();

        $newProduct->addImageToMediaGallery($imagePath,array('image','small_image','thumbnail'),true,false);

        return;
	}
}