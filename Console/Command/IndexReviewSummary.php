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

namespace Hgati\ProductReviews\Console\Command;

use Hgati\ProductReviews\Model\Review\Command\SummaryRateInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory;

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
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * summary index constructor.
     *
     * @param SummaryRateInterface $summaryRate
     * @param State $state
     * @param Registry $registry
     * @param CollectionFactory $collectionFactory
     * @param null $name
     */
    public function __construct(
        SummaryRateInterface $summaryRate,
        State $state,
        Registry $registry,
        CollectionFactory $collectionFactory,
        $name = null
    ) {
        parent::__construct($name);

        $this->summaryRate = $summaryRate;
        $this->state = $state;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Configures the current command.a
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('hgatireview:index-summary:process');
        $this->addOption(
            self::NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'Sku'
        );
        $this->setDescription('Process collect review ratings for product. Use query as this: php bin/magento hgatireview:index-summary:process --sku=product-sku');
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
            $output->writeln(sprintf('<info>are applying for product sku: %s</info>', $productSku));
            $this->summaryRate->execute($productSku);
        } else {
            $collection = $this->collectionFactory->create();
            $collection->getSelect()->group('entity_pk_value');
            $reviewItems = $collection->getItems();

            $output->writeln(sprintf('<info>are applying for ( %s ) product(s).</info>', count($reviewItems)));

            foreach ($reviewItems as $_review) {
                $this->summaryRate->execute("", (int)$_review->getEntityPkValue());
            }
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
