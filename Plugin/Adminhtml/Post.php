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

namespace Lof\ProductReviews\Plugin\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Review\Model\RatingFactory;
use Magento\Review\Model\ReviewFactory;
use Lof\ProductReviews\Model\CustomReviewFactory;
use Lof\ProductReviews\Model\GalleryFactory;
use Lof\ProductReviews\Model\Review\Command\SummaryRateInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Post extends \Magento\Review\Controller\Adminhtml\Product\Post
{
    /**
     * @var SummaryRateInterface
     */
    protected $summaryRate;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    protected $customReviewFactory;

    protected $galleryFactory;

    protected $productRepository;

    /**
     * Post constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ReviewFactory $reviewFactory
     * @param RatingFactory $ratingFactory
     * @param CustomReviewFactory $customReviewFactory
     * @param GalleryFactory $galleryFactory
     * @param SummaryRateInterface $summaryRate
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ReviewFactory $reviewFactory,
        RatingFactory $ratingFactory,
        CustomReviewFactory $customReviewFactory,
        GalleryFactory $galleryFactory,
        SummaryRateInterface $summaryRate,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context, $coreRegistry, $reviewFactory, $ratingFactory);
        $this->customReviewFactory = $customReviewFactory;
        $this->galleryFactory = $galleryFactory;
        $this->summaryRate = $summaryRate;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Magento\Review\Controller\Adminhtml\Product\Post $object
     * @param $resultRedirect
     * @return mixed
     */
    public function afterExecute(\Magento\Review\Controller\Adminhtml\Product\Post $object, $resultRedirect)
    {
        $review = $this->reviewFactory->create()->getCollection();
        $latestItem = $review->getLastItem()->getData();
        $data = $object->getRequest()->getPostValue();

        $dataCustomReview = [
            'advantages' => $data['advantages'],
            'disadvantages' => $data['disadvantages'],
            'email_address' => isset($data['email_address']) ? $data['email_address'] : '',
            'avatar_url' => isset($data['avatar_url']) ? $data['avatar_url'] : '',
            'review_id' => $latestItem['review_id'],
            'is_recommended' => isset($data['is_recommended']) ? (int)$data['is_recommended'] : 0,
            'verified_buyer' => isset($data['verified_buyer']) ? (int)$data['verified_buyer'] : 0
        ];

        $customModel = $this->customReviewFactory->create();
        $customModel->setData($dataCustomReview);
        $customModel->save();

        $modelGallery = $this->galleryFactory->create();
        $modelGallery->setReviewId($latestItem['review_id'])
            ->setLabel('Gallery of Review ' . $latestItem['review_id'])
            ->setStatus(\Lof\ProductReviews\Model\Gallery::STATUS_DISABLED)
            ->setValue(json_encode([]))
            ->save();

        /** Update review rating detailed summary */
        $productId = $this->getRequest()->getParam('product_id', false);
        $product = $this->productRepository->getById($productId);
        $this->summaryRate->execute($product->getSku(), $productId);

        $resultRedirect->setPath('review/*/');
        return $resultRedirect;
    }
}
