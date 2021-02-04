<?php
/**
 * *
 *  * Landofcoder
 *  *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the Landofcoder.com license that is
 *  * available through the world-wide-web at this URL:
 *  * https://landofcoder.com/license
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade this extension to newer
 *  * version in the future.
 *  *
 *  * @category   Landofcoder
 *  * @package    Lof_Formbuilder
 *  * @copyright  Copyright (c) 2020 Landofcoder (https://www.landofcoder.com/)
 *  * @license    https://landofcoder.com/LICENSE-1.0.html
 *
 */
namespace Lof\ProductReviews\Model\Reminders\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Lof\ProductReviews\Model\Reminders;

/**
 * Product status functionality model
 *
 * @api
 * @since 100.0.2
 */
class Status implements OptionSourceInterface
{
    /**#@+
     * Reminders Status values
     */
    const STATUS_PENDING = 1;
    const STATUS_ON_HOLD = 2;

    protected $_reviewReminder;

    public function __construct(Reminders $reviewReminder)
    {
        $this->_reviewReminder = $reviewReminder;
    }

    /**#@-*/

    /**
     * Retrieve Visible Status Ids
     *
     * @return int[]
     */
    public function getVisibleStatusIds()
    {
        return [self::STATUS_PENDING];
    }

    /**
     * Retrieve Saleable Status Ids
     * Default Product Enable status
     *
     * @return int[]
     */
    public function getSaleableStatusIds()
    {
        return [self::STATUS_PENDING];
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [self::STATUS_PENDING => __('Pending'), self::STATUS_ON_HOLD => __('On Hold')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_reviewReminder->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

}
