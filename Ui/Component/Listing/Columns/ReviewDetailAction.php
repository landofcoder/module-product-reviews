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

namespace Lof\ProductReviews\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ReviewDetailAction extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
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
                if (isset($item['review_id'])) {
                    $viewUrlPath = $this->getData('config/viewUrlPath') ?: '#';
                    $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'id';
                    $item[$this->getData('name')] = [
                        'view' => [
                            'href' => $this->urlBuilder->getUrl(
                                $viewUrlPath,
                                [
                                    $urlEntityParamName => $item['review_id']
                                ]
                            ),
                            'label' => __('View')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}