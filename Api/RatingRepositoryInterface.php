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
declare(strict_types=1);

namespace Hgati\ProductReviews\Api;

use Hgati\ProductReviews\Api\Data\ReviewInterface;
use Hgati\ProductReviews\Api\Data\ReplyInterface;
use Magento\Framework\Api\SearchCriteria;

/**
 * In Magento 2 Repository considered as an implementation of Facade pattern which provides a simplified interface
 * to a larger body of code responsible for Domain Entity management
 *
 * The main intention is to make API more readable and reduce dependencies of business logic code on the inner workings
 * of a module, since most code uses the facade, thus allowing more flexibility in developing the system
 *
 * Along with this such approach helps to segregate two responsibilities:
 * 1. Repository now could be considered as an API - Interface for usage (calling) in the business logic
 * 2. Separate class-commands to which Repository proxies initial call (like, Get Save GetList Delete) could be
 *    considered as SPI - Interfaces that you should extend and implement to customize current behaviour
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface RatingRepositoryInterface
{
    /**
     * get available rating data by rating code
     *
     * @param string|int|null $storeId current store code, store id
     * @return \Hgati\ProductReviews\Api\Data\RatingVoteInterface[]
     */
    public function getList(
        $storeId = null
    );
}
