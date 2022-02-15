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

declare(strict_types=1);

namespace Lof\ProductReviews\Console\Command;

use Lof\ProductReviews\Model\Review\Command\SummaryRateInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class IndexReviewSummary
 */
class IndexReviewSummary extends Command
{
    const NAME = "sku";

    /**
     * @var SummaryRateInterface
     */
    private $summaryRate;
    /**
     * @var State
     */
    private $state;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * summary index constructor.
     *
     * @param SummaryRateInterface $summaryRate
     * @param State $state
     * @param Registry $registry
     * @param null $name
     */
    public function __construct(
        SummaryRateInterface $summaryRate,
        State $state,
        Registry $registry,
        $name = null
    ) {
        parent::__construct($name);

        $this->summaryRate = $summaryRate;
        $this->state = $state;
        $this->registry = $registry;
    }

    /**
     * Configures the current command.a
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('lofreview:index-summary:process');
        $this->setDescription('Process collect review ratings for product. Use query as this: php bin/magento lofreview:index-summary:process --sku=product-sku');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->setAreaCode(Area::AREA_FRONTEND);
        } catch (\Exception $ex) {
            // fail gracefully
        }

        $start = $this->getCurrentMs();

        $productSku = $input->getOption(self::NAME);

        $output->writeln('<info>Initialization processing of reviews.</info>');
        $output->writeln(sprintf('<info>Started at %s</info>', (new \DateTime())->format('Y-m-d H:i:s')));
        $output->writeln('Processing...');

        if ($productSku) {
            $this->summaryRate->execute($productSku);
        } else {
            $output->writeln('<info>Notice: Require sku param!. Please use --sku=product-sku</info>');
        }

        $end = $this->getCurrentMs();

        $output->writeln(sprintf('<info>Finished at %s</info>', (new \DateTime())->format('Y-m-d H:i:s')));
        $output->writeln(sprintf('<info>Total execution time %sms</info>', $end - $start));

        return 0;
    }

    /**
     *
     * @return float|int
     */
    private function getCurrentMs()
    {
        $mt = explode(' ', microtime());

        return ((int) $mt[1]) * 1000 + ((int) round($mt[0] * 1000));
    }
}
