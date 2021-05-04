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

class Columns extends \Magento\Ui\Component\Listing\Columns
{
    /**
     * Default columns max order
     */
    const DEFAULT_COLUMNS_MAX_ORDER = 50;

    /**
     * @var array
     */
    protected $filterMap = [
        'default' => 'text',
        'select' => 'select',
        'boolean' => 'select',
        'multiselect' => 'select',
        'date' => 'dateRange',
    ];

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Catalog\Ui\Component\ColumnFactory $columnFactory
     * @param \Magento\Catalog\Ui\Component\Listing\Attribute\RepositoryInterface $attributeRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Lof\ProductReviews\Ui\Component\ColumnFactory $columnFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
        $this->columnFactory = $columnFactory;
    }
}
