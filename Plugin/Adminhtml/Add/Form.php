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

namespace Lof\ProductReviews\Plugin\Adminhtml\Add;

class Form extends \Magento\Review\Block\Adminhtml\Add\Form
{
    /**
     * @param \Magento\Review\Block\Adminhtml\Add\Form $object
     * @param $form
     * @return array
     */
    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Add\Form $object, $form)
    {
        $fieldset1 = $form->addFieldset(
            'add_lof_custom_review',
            ['legend' => __('Lof Custom Review')]
        );

        $fieldset1->addType(
            'images',
            '\Lof\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer\Image'
        );

        $fieldset1->addField(
            'email_address',
            'text',
            [
                'name' => 'email_address',
                'title' => __('Email Address'),
                'label' => __('Email Address'),
                'maxlength' => '200',
                'required' => false
            ]
        );

        $fieldset1->addField(
            'avatar_url',
            'text',
            [
                'name' => 'avatar_url',
                'title' => __('Avatar Url'),
                'label' => __('Avatar Url'),
                'maxlength' => '200',
                'required' => false
            ]
        );


        $fieldset1->addField(
            'advantages',
            'text',
            [
                'name' => 'advantages',
                'title' => __('What I like about this product'),
                'label' => __('What I like about this product'),
                'maxlength' => '100',
                'required' => false
            ]
        );

        $fieldset1->addField(
            'disadvantages',
            'text',
            [
                'name' => 'disadvantages',
                'title' => __('What I don\'t like about this product'),
                'label' => __('What I don\'t like about this product'),
                'maxlength' => '100',
                'required' => false
            ]
        );

        /*$fieldset1->addField(
            'review_images',
            'images',
            [
                'name' => 'review_images',
                'label' => __('Review Images'),
                'title' => __('Review Images'),
                'required' => false
            ]
        );*/

        return [$form];
    }
}
