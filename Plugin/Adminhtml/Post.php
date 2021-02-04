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
 *  * @package    Lof_ProductReviews
 *  * @copyright  Copyright (c) 2020 Landofcoder (https://www.landofcoder.com/)
 *  * @license    https://landofcoder.com/LICENSE-1.0.html
 *
 */

namespace Lof\ProductReviews\Plugin\Adminhtml;


use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Review\Model\RatingFactory;
use Magento\Review\Model\ReviewFactory;
use Lof\ProductReviews\Model\CustomReviewFactory;
use Lof\ProductReviews\Model\GalleryFactory;

class Post extends \Magento\Review\Controller\Adminhtml\Product\Post
{
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ReviewFactory $reviewFactory,
        RatingFactory $ratingFactory,
        CustomReviewFactory $customReviewFactory,
        GalleryFactory $galleryFactory
    ) {
        parent::__construct($context, $coreRegistry, $reviewFactory, $ratingFactory);
        $this->customReviewFactory = $customReviewFactory;
        $this->galleryFactory = $galleryFactory;
    }

    public function afterExecute(\Magento\Review\Controller\Adminhtml\Product\Post $object, $resultRedirect)
    {
        $review = $this->reviewFactory->create()->getCollection();
        $latestItem = $review->getLastItem()->getData();
        $data = $object->getRequest()->getPostValue();

        $dataCustomReview = [
            'advantages' => $data['advantages'],
            'disadvantages' => $data['disadvantages'],
            'email_address' => isset($data['email_address'])?$data['email_address']:'',
            'avatar_url' => isset($data['avatar_url'])?$data['avatar_url']:'',
            'review_id' => $latestItem['review_id']
        ];

        $customModel = $this->customReviewFactory->create();
        $customModel->setData($dataCustomReview);
        $customModel->save();

        $modelGallery = $this->galleryFactory->create();
        $modelGallery->setReviewId($latestItem['review_id'])
            ->setLabel('Gallery of Review '.$latestItem['review_id'])
            ->setStatus(2)
            ->setValue(json_encode([]))
            ->save();

        $resultRedirect->setPath('review/*/');
        return $resultRedirect;
    }
}