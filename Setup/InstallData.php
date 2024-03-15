<?php
/**
 * Hgati
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Hgati.com license that is
 * available through the world-wide-web at this URL:
 * https://hgati.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Hgati
 * @package    Hgati_ProductReviews
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */

namespace Hgati\ProductReviews\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Review\Model\ResourceModel\Review\CollectionFactory
     */
    protected $_reviewsColFactory;

    /**
     * @var \Hgati\ProductReviews\Model\CustomReviewFactory
     */
    protected $_customReviewFactory;

    /**
     * @var \Hgati\ProductReviews\Model\GalleryFactory
     */
    protected $_galleryFactory;

    /**
     * InstallData constructor.
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory
     * @param \Hgati\ProductReviews\Model\CustomReviewFactory $customReviewFactory
     * @param \Hgati\ProductReviews\Model\GalleryFactory $galleryFactory
     */
    public function __construct(
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \Hgati\ProductReviews\Model\CustomReviewFactory $customReviewFactory,
        \Hgati\ProductReviews\Model\GalleryFactory $galleryFactory
    ) {
        $this->_reviewsColFactory = $collectionFactory;
        $this->_customReviewFactory = $customReviewFactory;
        $this->_galleryFactory = $galleryFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();

        $reviewItems = $this->_reviewsColFactory->create()->getItems();

        foreach ($reviewItems as $item) {

            $id = $item->getId();
            $average = $this->_customReviewFactory->create()->addCountRating($id);

            $this->_customReviewFactory->create()
                ->setAverage($average)
                ->setReviewId($id)
                ->save();

            $this->_galleryFactory->create()
                ->setReviewId($id)
                ->setLabel('Gallery of Review '.$id)
                ->setValue(json_encode([]))
                ->setStatus(2)
                ->save();
        }

        $setup->endSetup();
    }
}
