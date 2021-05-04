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

namespace Lof\ProductReviews\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ProductName extends Column
{
    /** Url path */
    const PRODUCT_URL_PATH_EDIT = 'catalog/product/edit';
    const PRODUCT_URL_PATH_DELETE = 'catalog/product/delete';

    /**
     * @var
     */
    protected $actionUrlBuilder;

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * ProductName constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param \Magento\catalog\Model\ProductFactory $product
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        \Magento\catalog\Model\ProductFactory $product,
        $editUrl = self::PRODUCT_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        $this->_product = $product;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['product_id'])) {
                    $product = $this->_product->create()->load($item['product_id']);
                    $item[$name . '_html'] = '<a href="'.$this->urlBuilder->getUrl($this->editUrl, ['id' => $item['product_id']]).'" onclick="window.location=this.href; return true;">' . $product->getName() . '</a>';
                }
            }
        }
        return $dataSource;
    }
}
