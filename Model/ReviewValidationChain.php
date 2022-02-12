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

namespace Lof\ProductReviews\Model;

use Lof\ProductReviews\Api\Data\ReviewInterface;
use Lof\ProductReviews\Validation\ValidationResult;
use Lof\ProductReviews\Validation\ValidationResultFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Chain of validators. Extension point for new validators via di configuration
 *
 * @api
 */
class ReviewValidationChain implements ReviewValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @var StockValidatorInterface[]
     */
    private $validators;

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param ReviewValidatorInterface[] $validators
     * @throws LocalizedException
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        array $validators = []
    ) {
        $this->validationResultFactory = $validationResultFactory;

        foreach ($validators as $validator) {
            if (!$validator instanceof ReviewValidatorInterface) {
                throw new LocalizedException(
                    __('Review Validator must implement ReviewValidatorInterface.')
                );
            }
        }

        $this->validators = $validators;
    }

    /**
     * @inheritdoc
     *
     * @param ReviewInterface $stock
     *
     * @return ValidationResult
     */
    public function validate(ReviewInterface $stock): ValidationResult
    {
        $errors = [];

        foreach ($this->validators as $validator) {
            $validationResult = $validator->validate($stock);

            if (!$validationResult->isValid()) {
                $errors[] = $validationResult->getErrors();
            }
        }

        $errors = count($errors) ? array_merge(...$errors) : [];

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
