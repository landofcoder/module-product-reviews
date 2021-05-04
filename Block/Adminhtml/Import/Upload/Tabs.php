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
namespace Lof\ProductReviews\Block\Adminhtml\Import\Upload;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('import_tab');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Upload'));

        $this->addTab(
            'upload_review',
            [
                'label' => __('Upload Review'),
                'content' => $this->getLayout()->createBlock('Lof\ProductReviews\Block\Adminhtml\Import\Upload\Tab\Main')->toHtml()
            ]
        );
    }

}
