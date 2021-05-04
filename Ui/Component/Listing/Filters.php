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
namespace Lof\ProductReviews\Ui\Component\Listing;

use Lof\ProductReviews\Ui\Component\FilterFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
use Magento\Framework\View\Element\UiComponent\ObserverInterface;
use Magento\Framework\View\Element\UiComponentInterface;

/**
 * @api
 * @since 101.0.0
 */
class Filters implements ObserverInterface
{
    /**
     * @var FilterFactory
     * @since 101.0.0
     */
    protected $filterFactory;

    /**
     * @param FilterFactory $filterFactory
     * @param CollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        FilterFactory $filterFactory
    ) {
        $this->filterFactory = $filterFactory;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function update(UiComponentInterface $component)
    {
        if (!$component instanceof \Magento\Ui\Component\Filters) {
            return;
        }
    }
}
