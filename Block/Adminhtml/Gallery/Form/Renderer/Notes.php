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

namespace Lof\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer;

use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Escaper;
use Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollection;
use Magento\Framework\App\RequestInterface;

class Notes extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * @var CustomReviewCollection
     */
    protected $_customReviewCollection;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Notes constructor.
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param CustomReviewCollection $customReviewCollection
     * @param RequestInterface $request
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        CustomReviewCollection $customReviewCollection,
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('notes');
        $this->_customReviewCollection = $customReviewCollection;
        $this->_request = $request;
    }

    /**
     * Retrieve Element HTML
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = 'Information will be shown after saving.';
        $customReview = $this->_getCustomReview();
        if (!empty($customReview)) {
            $html = '<div id="lof_review_report"><b>' . $customReview['report_abuse'] . '</b>' . __(' abuse reports submitted.') . '</div>';
            $html .= '<div id="lof_review_helpful"><b>' . $customReview['count_helpful'] . '</b>' . __(' people found this helpful.') . '</div>';
            $html .= '<div id="lof_review_unhelpful"><b>' . $customReview['count_unhelpful'] . '</b>' . __(' people found this unhelpful.') . '</div>';
        }
        return $html;
    }

    /**
     * @return array
     */
    protected function _getCustomReview()
    {
        $custom = [];

        $collection = $this->_customReviewCollection->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'review_id',
            ['in' => $this->_request->getParam('id')]
        );

        foreach ($collection as $data) {
            $custom = $data->getData();
        }

        return $custom;
    }
}
