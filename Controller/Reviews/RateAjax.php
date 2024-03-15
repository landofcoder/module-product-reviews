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

namespace Hgati\ProductReviews\Controller\Reviews;

class RateAjax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Hgati\ProductReviews\Model\CustomReviewFactory
     */
    protected $customReviewFactory;

    /**
     * @var \Hgati\ProductReviews\Model\RateReportFactory
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
     * RateAjax constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\HTTP\Header $httpHeader
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Hgati\ProductReviews\Model\CustomReviewFactory $customReviewFactory
     * @param \Hgati\ProductReviews\Model\RateReportFactory $rateReportFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\HTTP\Header $httpHeader,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Customer\Model\Session $customerSession,
        \Hgati\ProductReviews\Model\CustomReviewFactory $customReviewFactory,
        \Hgati\ProductReviews\Model\RateReportFactory $rateReportFactory
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
        $choice = $this->getRequest()->getPost('choice');
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
            $info['helpful'] = $data->getCountHelpful();
            $info['unhelpful'] = $data->getCountUnhelpful();
            $info['total'] = $data->getTotalHelpful();
        }
        if ($modelCustom->getCollection()->getItemByColumnValue('review_id', $reviewId)) {
            $custom = $modelCustom->load($info['id']);
            if ($choice == 'helpful') {
                $custom->setCountHelpful($info['helpful'] + 1)
                    ->setTotalHelpful($info['total'] + 1)
                    ->save();
            } else {
                $choice = "unhelpful";
                $custom->setCountUnhelpful($info['unhelpful'] + 1)
                    ->setTotalHelpful($info['total'] + 1)
                    ->save();
            }
        } else {
            if ($choice == 'helpful') {
                $modelCustom->setCountHelpful(1)
                    ->setTotalHelpful(1)
                    ->setReviewId($reviewId)
                    ->save();
            } else {
                $choice = "unhelpful";
                $modelCustom->setCountUnhelpful(1)
                    ->setTotalHelpful(1)
                    ->setReviewId($reviewId)
                    ->save();
            }
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
            $model->setRateType($choice);
            $model->save();
        } else {
            $rateReportModel->setReviewId($reviewId)
                ->setCustomerId($customerId)
                ->setIpAddress($customerIpAddress)
                ->setBrowserData($userAgent)
                ->setRateType($choice)
                ->save();
        }
        return;
    }
}
