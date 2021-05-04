<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
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
