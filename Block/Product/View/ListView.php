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

namespace Hgati\ProductReviews\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Hgati\ProductReviews\Model\ReviewReply;

class ListView extends \Magento\Review\Block\Product\View\ListView
{

    /**
     * @var \Hgati\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory
     */
    protected $_galleryFactory;

    /**
     * @var \Hgati\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory
     */
    protected $_customFactory;

    /**
     * @var \Hgati\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory
     */
    protected $_replyFactory;

    /**
     * @var \Hgati\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory
     */
    protected $_rateReportFactory;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Hgati\ProductReviews\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\App\ScopeInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $_imageFactory;

    /**
     * ListView constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory
     * @param \Hgati\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory $galleryColFactory
     * @param \Hgati\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory $customColFactory
     * @param \Hgati\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory $replyColFactory
     * @param \Hgati\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory $rateColFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Hgati\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory $galleryColFactory,
        \Hgati\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory $customColFactory,
        \Hgati\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory $replyColFactory,
        \Hgati\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory $rateColFactory,
        \Hgati\ProductReviews\Helper\Data $helperData,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {

        $this->_galleryFactory = $galleryColFactory;
        $this->_customFactory = $customColFactory;
        $this->_replyFactory = $replyColFactory;
        $this->_rateReportFactory = $rateColFactory;
        $this->_storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->remoteAddress = $remoteAddress;
        $this->_fileSystem = $fileSystem;
        $this->_imageFactory = $imageFactory;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $collectionFactory,
            $data
        );
    }

    /**
     * @return mixed
     */
    public function getCustomCollection()
    {
        return $this->_customFactory->create();
    }

    /**
     * @param $reviewId
     * @return \Hgati\ProductReviews\Model\ResourceModel\CustomReview\Collection
     */
    public function getReviewsCustom($reviewId)
    {
        $customCollection = $this->getCustomCollection();
        $customize = $customCollection->addFieldToFilter('review_id', $reviewId);
        return $customize;
    }

    /**
     * @param $reviewId
     * @return \Hgati\ProductReviews\Model\ResourceModel\CustomReview\Collection
     */
    public function getHelpful($reviewId)
    {
        $result = [];

        $customCollection = $this->getCustomCollection();
        $customize = $customCollection->addFieldToFilter('review_id', $reviewId);
        foreach ($customize as $data) {
            $result['helpful'] = $data->getCountHelpful();
            $result['total'] = $data->getTotalHelpful();
        }

        return $result;
    }

    /**
     * @param $reviewId
     * @return mixed|array|mixed
     */
    public function getReviewsGallery($reviewId)
    {
        $reviewGalleries = $this->_galleryFactory->create();
        $gallery = $reviewGalleries->addFieldToFilter('review_id', $reviewId);
        $imageName = [];
        foreach ($gallery as $value) {
            $imageName = json_decode($value->getValue());
        }
        return $imageName;
    }

    /**
     * @param string $image
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl($image)
    {
        $parsed = parse_url($image);
        if (!empty($parsed['scheme'])) {
            return $image;
        }
        $mediaUrl = $this->_storeManager
                ->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'hgati/product_reviews/' . ltrim($image, '/');

        return $mediaUrl;
    }

    /**
     * @param string $image
     * @param int|float|string $width
     * @param int|float|string $height
     * @return string
     */
    public function resizeImage($image, $width, $height)
    {
        $parsed = parse_url($image);
        if (!empty($parsed['scheme'])) {
            return $image;
        }
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('hgati/product_reviews/') . $image;
        if (!file_exists($absolutePath)) {
            return false;
        }
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/' . $width . '/') . $image;
        if (!file_exists($imageResized)) {
            //create image factory...
            $imageResize = $this->_imageFactory->create();
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(true);
            $imageResize->keepTransparency(true);
            $imageResize->keepFrame(false);
            $imageResize->keepAspectRatio(true);
            $imageResize->resize($width, $height);
            //destination folder
            $destination = $imageResized;
            //save image
            $imageResize->save($destination);
        }
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'resized/' . $width . '/' . $image;
        return $resizedURL;
    }

    /**
     * @param $reviewId
     * @return \Hgati\ProductReviews\Model\ResourceModel\ReviewReply\Collection
     */
    public function getReviewsReply($reviewId)
    {
        $reviewReply = $this->_replyFactory->create();
        $reply = $reviewReply
                ->addFieldToFilter('review_id', $reviewId)
                ->addFieldToFilter('status', ReviewReply::STATUS_ENABLED);
        return $reply;
    }

    /**
     * @return mixed
     */
    public function getImgWidth()
    {
        $imageWidth = $this->helperData->getImageWidth();
        return !empty($imageWidth) ? $imageWidth : 150;
    }

    /**
     * @return mixed
     */
    public function getImgHeight()
    {
        $imageHeight = $this->helperData->getImageHeight();
        return !empty($imageHeight) ? $imageHeight : 150;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFrontendUrl()
    {
        $frontendUrl = $this->_storeManager->getStore()->getBaseUrl();
        return $frontendUrl;
    }

    /**
     * @param $reviewId
     * @return mixed|array
     */
    public function _compareUserData($reviewId)
    {
        $result = [];
        $userIp = $this->remoteAddress->getRemoteAddress();
        $rateReport = $this->_rateReportFactory->create();
        $data = $rateReport->addFieldToFilter('review_id', $reviewId)->getData();
        if (!empty($data)) {
            foreach ($data as $userData) {
                if ($userData['ip_address'] == $userIp) {
                    $result['rate_type'] = isset($userData['rate_type']) ? $userData['rate_type'] : '';
                    $result['report_type'] = isset($userData['report_type']) ? $userData['report_type'] : '';
                }
            }
        }
        return $result;
    }

    /**
     * @param $reviewId
     * @return string
     */
    public function getGalleryStatus($reviewId)
    {
        $status = '';
        $modelGallery = $this->_galleryFactory->create();
        $data = $modelGallery->addFieldToFilter('review_id', $reviewId)->getData();

        if (!empty($data)) {
            foreach ($data as $value) {
                $status = $value['status'];
            }
        }
        return $status;
    }

    /**
     * @param $path
     * @return bool
     */
    public function getIsSetFlagConfig($path)
    {
        return $this->scopeConfig->isSetFlag($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getValueConfig($path)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $type
     * @return bool|\Magento\Framework\DataObject[]
     */
    public function getReviewsCollectionByType($type = 'helpful')
    {
        if ($type == 'helpful') {
            $collection = $this->_reviewsColFactory->create()->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product',
                $this->getProduct()->getId()
            );
            $collection->getSelect()->joinLeft(
                ['hgati_customize' => $this->_reviewsCollection->getTable('hgati_review_customize')],
                'main_table.review_id = hgati_customize.review_id',
                ['count_helpful']
            );
            $items = $collection->setOrder(
                'count_helpful',
                'DESC'
            )->addRateVotes()->getItems();

            return $items;

        } elseif ($type == 'rating') {
            $collection = $this->_reviewsColFactory->create()->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product',
                $this->getProduct()->getId()
            );
            $collection->getSelect()->joinLeft(
                ['hgati_customize' => $this->_reviewsCollection->getTable('hgati_review_customize')],
                'main_table.review_id = hgati_customize.review_id',
                ['average']
            );
            $items = $collection->setOrder(
                'average',
                'DESC'
            )->addRateVotes()->getItems();

            return $items;

        } elseif ($type == 'default') {
            return $this->_reviewsCollection->getItems();
        } else {
            return false;
        }
    }

    /**
     * Get URL for ajax call
     *
     * @return string
     */
    public function getProductReviewUrl()
    {
        return $this->getUrl(
            'review/product/listAjax',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId()
            ]
        );
    }
}
