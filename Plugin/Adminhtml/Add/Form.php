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

namespace Hgati\ProductReviews\Plugin\Adminhtml\Add;

class Form extends \Magento\Review\Block\Adminhtml\Add\Form
{
    /**
     * @param \Magento\Review\Block\Adminhtml\Add\Form $object
     * @param $form
     * @return mixed|array
     */
    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Add\Form $object, $form)
    {
        $fieldset1 = $form->addFieldset(
            'add_hgati_custom_review',
            ['legend' => __('Hgati Custom Review')]
        );

        $fieldset1->addType(
            'images',
            '\Hgati\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer\Image'
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

        // $fieldset1->addField(
        //     'avatar_url',
        //     'text',
        //     [
        //         'name' => 'avatar_url',
        //         'title' => __('Avatar Url'),
        //         'label' => __('Avatar Url'),
        //         'maxlength' => '200',
        //         'required' => false
        //     ]
        // );


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

        $fieldset1->addField(
            'is_recommended',
            'select',
            [
                'label' => __('Is Recommended?'),
                'title' => __('Is Recommended?'),
                'name' => 'is_recommended',
                'options' => [
                    '0' => __('No'),
                    '1' => __('Yes')
                ],
                'required' => false
            ]
        );

        $fieldset1->addField(
            'verified_buyer',
            'select',
            [
                'label' => __('Verified Buyer?'),
                'title' => __('Verified Buyer?'),
                'name' => 'verified_buyer',
                'options' => [
                    '0' => __('No'),
                    '1' => __('Yes')
                ],
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
