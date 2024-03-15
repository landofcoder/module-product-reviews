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

use Magento\Framework\Data\Form\Element\Image as ImageField;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\CollectionFactory as ElementCollectionFactory;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Hgati\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollection;
use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;

class Image extends ImageField
{

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var GalleryCollection
     */
    protected $_galleryModel;

    /**
     * @var DecoderInterface
     */
    protected $_jsonDecoder;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Image constructor.
     * @param RequestInterface $request
     * @param GalleryCollection $galleryModel
     * @param DecoderInterface $jsonDecoder
     * @param StoreManagerInterface $storeManager
     * @param ElementFactory $factoryElement
     * @param ElementCollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param UrlInterface $urlBuilder
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Model\UrlInterface $backendUrl,
        RequestInterface $request,
        GalleryCollection $galleryModel,
        DecoderInterface $jsonDecoder,
        StoreManagerInterface $storeManager,
        ElementFactory $factoryElement,
        ElementCollectionFactory $factoryCollection,
        Escaper $escaper,
        UrlInterface $urlBuilder,
        $data = []
    ) {
        $this->_backendUrl = $backendUrl;
        $this->_request = $request;
        $this->_galleryModel = $galleryModel;
        $this->_jsonDecoder = $jsonDecoder;
        $this->_storeManager = $storeManager;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $urlBuilder, $data);
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        $galleryLink = 'Information will be shown after saving.';
        if ($this->_getGallery() && is_array($this->_getGallery())) {
            $gallery = $this->_getGallery();

            $imageName = $this->_jsonDecoder->decode($gallery['value']);
            $i = 0;
            foreach ($imageName as $data) {
                $url = $data;
                if (!preg_match("/^http\:\/\/|https\:\/\//", $data)) {
                    $url = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . 'hgati/product_reviews/' . $data;
                }
                echo '<a href="' .
                    $url .
                    '"' .
                    ' onclick="imagePreview(\'' .
                    $this->getHtmlId() .
                    '_image\'); return false;" ' .
                    $this->_getUiId(
                        'link'
                    ) .
                    '>' .
                    '<img src="' .
                    $url .
                    '" id="' .
                    $this->getHtmlId() .
                    '_image" title="' .
                    $gallery['label'] .
                    '"' .
                    ' alt="' .
                    $gallery['label'] .
                    '" height="150" width="150" class="small-image-preview v-middle"  ' .
                    $this->_getUiId() .
                    ' />' .
                    '</a> ';
                $i++;
            }

            $editLink =  $this->_backendUrl->getUrl('hgati_product_reviews/gallery/edit', ['id' => $gallery['id']]);
            $deleteLink = $this->_backendUrl->getUrl('hgati_product_reviews/gallery/delete', ['id' => $gallery['id']]);

            $galleryLink = '<a href="' . $editLink . '">' . __('Add/Edit Gallery') .'</a> ';
            $galleryLink .= !empty($imageName[0]) ? '<a href="' . $deleteLink . '">' . __('Delete Gallery') .'</a> ' : '';
        }
        return $galleryLink;
    }

    /**
     * @return mixed|array
     */
    protected function _getGallery()
    {
        $gallery = [];

        $collection = $this->_galleryModel->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'review_id',
            ['in' => $this->_request->getParam('id')]
        );

        foreach ($collection as $data) {
            $gallery = $data->getData();
        }

        return $gallery;
    }
}
