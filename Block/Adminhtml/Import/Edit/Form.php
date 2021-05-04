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
namespace Lof\ProductReviews\Block\Adminhtml\Import\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected function _prepareForm()
    {
       /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
            'data' => [
            'id' => 'edit_form',
            'action' => $this->getData('action'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
            ]
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
