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

namespace Hgati\ProductReviews\Block\Adminhtml\Gallery\Form\Renderer;

use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Escaper;
use Hgati\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory as CustomReviewCollection;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\UrlInterface;

class NoteLink extends \Magento\Framework\Data\Form\Element\AbstractElement
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
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * NoteLink constructor.
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param CustomReviewCollection $customReviewCollection
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        CustomReviewCollection $customReviewCollection,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('notelink');
        $this->_customReviewCollection = $customReviewCollection;
        $this->_request = $request;
        $this->_urlBuilder = $urlBuilder;
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
            $link = $this->_urlBuilder->getUrl("hgati_product_reviews/replies/index", ["review_id" => $customReview["review_id"]]);
            $html = '<div id="hgati_review_link"><b><a href="'.$link.'">' . __("View More Replies") . '</a></b></div>';
        }
        return $html;
    }

    /**
     * @return mixed|array
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
