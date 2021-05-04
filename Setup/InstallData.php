<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ProductReviews
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\ProductReviews\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $_reviewsColFactory;
    protected $_customReviewFactory;
    protected $_galleryFactory;

    public function __construct(
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \Lof\ProductReviews\Model\CustomReviewFactory $customReviewFactory,
        \Lof\ProductReviews\Model\GalleryFactory $galleryFactory
    )
    {
        $this->_reviewsColFactory = $collectionFactory;
        $this->_customReviewFactory = $customReviewFactory;
        $this->_galleryFactory = $galleryFactory;
    }

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
