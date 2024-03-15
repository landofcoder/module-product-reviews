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
 * @copyright  Copyright (c) 2022 Hgati (https://hgati.com/)
 * @license    https://hgati.com/terms
 */
declare(strict_types=1);

namespace Hgati\ProductReviews\Model\Review\Validator;

use Hgati\ProductReviews\Api\Data\ReviewInterface;
use Hgati\ProductReviews\Validation\ValidationResult;
use Hgati\ProductReviews\Validation\ValidationResultFactory;
use Hgati\ProductReviews\Model\ReviewValidatorInterface;

/**
 * Class TitleValidator - validates review stores
 */
class StoresValidator implements ReviewValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @param ValidationResultFactory $validationResultFactory
     */
    public function __construct(ValidationResultFactory $validationResultFactory)
    {
        $this->validationResultFactory = $validationResultFactory;
    }

    /**
     * Check if review has stores set
     *
     * @param ReviewInterface $review
     *
     * @return ValidationResult
     */
    public function validate(ReviewInterface $review): ValidationResult
    {
        $value = (array)$review->getStores();
        $errors = [];

        if (empty($value)) {
            $errors[] = __('"%field" can not be empty.', ['field' => ReviewInterface::STORES]);
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
