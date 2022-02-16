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

namespace Lof\ProductReviews\Controller\Reviews;

class ReportAjax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Lof\ProductReviews\Model\CustomReviewFactory
     */
    protected $customReviewFactory;

    /**
     * @var \Lof\ProductReviews\Model\RateReportFactory
     */
    protected $rateReportFactory;

    /**
     * @var \Magento\Framework\HTTP\Header
     */
    protected $httpHeader;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * ReportAjax constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\HTTP\Header $httpHeader
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Lof\ProductReviews\Model\CustomReviewFactory $customReviewFactory
     * @param \Lof\ProductReviews\Model\RateReportFactory $rateReportFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\HTTP\Header $httpHeader,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Customer\Model\Session $customerSession,
        \Lof\ProductReviews\Model\CustomReviewFactory $customReviewFactory,
        \Lof\ProductReviews\Model\RateReportFactory $rateReportFactory
    ) {
        $this->httpHeader = $httpHeader;
        $this->remoteAddress = $remoteAddress;
        $this->customerSession = $customerSession;
        $this->customReviewFactory = $customReviewFactory;
        $this->rateReportFactory = $rateReportFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $reportType = $this->getRequest()->getPost('type');
        $reportType = "report";
        $reviewId = $this->getRequest()->getPost('reviewId');
        $userAgent = $this->httpHeader->getHttpUserAgent();
        $customerIpAddress = $this->remoteAddress->getRemoteAddress();
        $getId = $this->customerSession->getId();
        $customerId = !empty($getId) ? $getId : 0;

        $modelCustom = $this->customReviewFactory->create();
        $collection = $modelCustom->getCollection()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'review_id',
            ['in' => $reviewId]
        );

        $info = [];
        foreach ($collection as $data) {
            $info['id'] = $data->getReviewCustomizeId();
            $info['report'] = $data->getReportAbuse();
        }
        if ($modelCustom->getCollection()->getItemByColumnValue('review_id', $reviewId)) {
            $custom = $modelCustom->load($info['id']);
            $custom->setReportAbuse($info['report'] + 1)
                ->save();
        } else {
            $modelCustom->setReportAbuse(1)
                ->setReviewId($reviewId)
                ->save();
        }

        $rateReportModel = $this->rateReportFactory->create();
        $rateData = $rateReportModel->getCollection()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'review_id',
            ['in' => $reviewId]
        );

        $value = [];
        foreach ($rateData as $data) {
            $value['id'] = $data->getId();
            $value['ip_address'] = $data->getIpAddress();
        }


        if (!empty($value['id'])) {
            $model = $rateReportModel->load($value['id']);
            $model->setReportType($reportType);
            $model->save();
        } else {
            $rateReportModel->setReviewId($reviewId)
                ->setCustomerId($customerId)
                ->setIpAddress($customerIpAddress)
                ->setBrowserData($userAgent)
                ->setReportType($reportType)
                ->save();
        }

        return;
    }
}
