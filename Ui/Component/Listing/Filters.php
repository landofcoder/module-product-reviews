<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
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
