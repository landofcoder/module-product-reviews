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
 * @copyright  Copyright (c) 2022 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Model\Review\Validator;

use Lof\ProductReviews\Api\Data\ReviewInterface;
use Lof\ProductReviews\Validation\ValidationResult;
use Lof\ProductReviews\Validation\ValidationResultFactory;
use Lof\ProductReviews\Model\ReviewValidatorInterface;

/**
 * Class TitleValidator - validates review details
 */
class DetailValidator implements ReviewValidatorInterface
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
     * Check If review details is not empty
     *
     * @param ReviewInterface $review
     *
     * @return ValidationResult
     */
    public function validate(ReviewInterface $review): ValidationResult
    {
        $value = (string)$review->getDetail();
        $errors = [];

        if (trim($value) === '') {
            $errors[] = __('"%field" can not be empty.', ['field' => ReviewInterface::DETAIL]);
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
