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

namespace Lof\ProductReviews\Plugin\Adminhtml\Edit;

use Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollection;
use Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory as ReviewReplyCollection;

class Form extends \Magento\Review\Block\Adminhtml\Edit\Form
{
    /**
     * @var CustomReviewCollection
     */
    protected $_customReviewFactory;

    /**
     * @var ReviewReplyCollection
     */
    protected $_reviewReplyFactory;

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Review\Helper\Data $reviewData
     * @param CustomReviewCollection $customReviewFactory
     * @param ReviewReplyCollection $reviewReplyFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Review\Helper\Data $reviewData,
        CustomReviewCollection $customReviewFactory,
        ReviewReplyCollection $reviewReplyFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $systemStore,
            $customerRepository,
            $productFactory,
            $reviewData,
            $data
        );
        $this->_customReviewFactory = $customReviewFactory;
        $this->_reviewReplyFactory = $reviewReplyFactory;
    }

    /**
     * @param \Magento\Review\Block\Adminhtml\Edit\Form $object
     * @param $form
     * @return mixed|array
     */
    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Edit\Form $object, $form)
    {
        $review = $object->_coreRegistry->registry('review_data');
        $reviewId = $this->_request->getParam('id');

        $customReview = $this->_customReviewFactory->create()->addFieldToSelect('*')->addFieldToFilter(
            'review_id',
            ['in' => $reviewId]
        );
        $custom = [];
        if ($customReview->count()) {
            $foundItem = $customReview->getFirstItem();
            $custom = $foundItem->getData();
        }

        $reviewReply = $this->_reviewReplyFactory->create()->addFieldToSelect('*')
        ->addFieldToFilter(
            'review_id',
            ['in' => $reviewId]
        )
        ->addFieldToFilter('admin_user_id', ["gt" => 0]);

        $reply = [];
        if ($reviewReply->count()) {
            $foundItem = $reviewReply->getFirstItem();
            $reply = $foundItem->getData();
        }

        $fieldset1 = $form->addFieldset(
            'add_lof_custom_review',
            ['legend' => __('Lof Custom Review')]
        );

        $fieldset1->addType(
            'images',
            '\Lof\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer\Image'
        );

        $fieldset1->addType(
            'notes',
            '\Lof\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer\Notes'
        );



        $fieldset1->addField(
            'review_customize_id',
            'hidden',
            [
                'name' => 'review_customize_id',
                'value' => !empty($custom) ? $custom['review_customize_id'] : 0
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
            'review_images',
            'images',
            [
                'name' => 'review_images',
                'label' => __('Review Images'),
                'title' => __('Review Images'),
                'required' => false
            ]
        );

        $fieldset1->addField(
            'review_notes',
            'notes',
            [
                'name' => 'review_notes',
                'label' => __('Review Notes'),
                'title' => __('Review Notes'),
                'required' => false
            ]
        );

        $fieldset1->addField(
            'answer',
            'textarea',
            [
                'name' => 'answer',
                'title' => __('Admin Answer'),
                'label' => __('Admin Answer'),
                'maxlength' => '250',
                'required' => false
            ]
        );

        if (!empty($review->getCustomerId())) {

            $fieldset2 = $form->addFieldset(
                'add_review_comment',
                ['legend' => __('Review Comment')]
            );

            $fieldset2->addType(
                'notelink',
                '\Lof\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer\NoteLink'
            );

            $fieldset2->addField(
                'reply_id',
                'hidden',
                [
                    'name' => 'reply_id',
                    'value' => isset($reply['reply_id']) ? $reply['reply_id'] : '0'
                ]
            );

            $fieldset2->addField(
                'customer_id',
                'hidden',
                [
                    'name' => 'customer_id',
                    'value' => $review->getCustomerId()
                ]
            );

            $fieldset2->addField(
                'reply_title',
                'text',
                [
                    'name' => 'reply_title',
                    'title' => __('Title'),
                    'label' => __('Title'),
                    'maxlength' => '150',
                    'required' => false
                ]
            );

            $fieldset2->addField(
                'reply_comment',
                'textarea',
                [
                    'name' => 'reply_comment',
                    'title' => __('Comment'),
                    'label' => __('Comment'),
                    'required' => false
                ]
            );

            $fieldset2->addField(
                'send_to',
                'checkbox',
                [
                    'name' => 'send_to',
                    'title' => __('Send to customer'),
                    'label' => __('Send to customer'),
                    'required' => false,
                    'onchange' => 'this.value = this.checked;'
                ]
            );

            $fieldset2->addField(
                'review_notes_link',
                'notelink',
                [
                    'name' => 'review_notes_link',
                    'label' => __('Manage Replies'),
                    'title' => __('Manage Replies'),
                    'required' => false
                ]
            );
        }

        $reviews = $review->getData();
        $reviews = array_merge($reviews, $custom);
        $reviews = array_merge($reviews, $reply);
        $form->setValues($reviews);
        return [$form];
    }
}
