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
namespace Hgati\ProductReviews\Block\Adminhtml\Import\Upload;

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
                'content' => $this->getLayout()->createBlock('Hgati\ProductReviews\Block\Adminhtml\Import\Upload\Tab\Main')->toHtml()
            ]
        );
    }
}
