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
namespace Lof\ProductReviews\Model\Config\Source;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

/**
 * Config category source
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ChooseCoupon implements \Magento\Framework\Option\ArrayInterface
{

    protected $ruleFactory;



    public function __construct(
        \Magento\SalesRule\Model\RuleFactory $ruleFactory) {
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function toOptionArray($addEmpty = true)
    {

        $collection = $this->ruleFactory->create()->getCollection();
        $options = [];
        foreach ($collection as $rule) {
            $options[] = ['label' => $rule->getName(), 'value' => $rule->getId()];
        }

        return $options;
    }
}
