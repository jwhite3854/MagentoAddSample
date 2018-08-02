<?php
/**
 * @author Julia White
 * @package JW_GenerateSamples
 */
class JW_GenerateSamples_Model_Indexer_Samples_Generator extends Mage_Index_Model_Indexer_Abstract
{
    /**
     * Retrieve Indexer name
     *
     * @return string
     */
    public function getName() {
        return Mage::helper('jw_generatesamples')->__('Samples Generator');
    }
    /**
     * Retrieve Indexer description
     *
     * @return string
     */
    public function getDescription() {
        return Mage::helper('jw_generatesamples')->__('Generate new sample products from catalog');
    }
    /**
     * Register data required by process in event object
     *
     * @param Mage_Index_Model_Event $event
     */
    protected function _registerEvent(Mage_Index_Model_Event $event)
    {

    }

    public function matchEvent(Mage_Index_Model_Event $event)
    {
        return false;
    }
    /**
     * Process event
     *
     * @param Mage_Index_Model_Event $event
     */
    protected function _processEvent(Mage_Index_Model_Event $event)
    {

    }

    public function reindexAll()
    {
        return $this->doReindexAll();
    }

    protected function doReindexAll() {

        Mage::log('Begin Generate Sample Product', 6, 'jw_generateSamples.log');
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
        Mage::log('Complete Generate Sample Product; '.$ct.' samples created. ', 6, 'jw_generateSamples.log');
        return true;
    }
}