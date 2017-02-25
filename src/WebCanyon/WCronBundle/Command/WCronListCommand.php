<?php

namespace WebCanyon\WCronBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Repository\CronRepository;
use WebCanyon\WCronBundle\Repository\RepositoryService;

/**
 * Class WCronListCommand
 *
 * @package WebCanyon\WCronBundle\Command
 */
class WCronListCommand extends AbstractCommand
{
    /**
     * 
     */
    protected function configure()
    {
        $this
            ->setName('wcron:list')
            ->setDescription('Display cron jobs.')
            ->addOption(
                'type',
                null,
                InputOption::VALUE_OPTIONAL,
                "Show by type option.\n
                   exception: Will sow only suspended by exception cron jobs.\n
                   suspended: Will sow only suspended cron jobs.\n
                   pending: Will sow only pending cron jobs.\n
                   running: Will sow only running cron jobs.\n
               "
            )
        ;
    }

    /**
     *
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->addStyles($output);

        /** @var RepositoryService $repositoryService */
        $repositoryService = $this->getContainer()->get('wcron.repository');
        /** @var CronRepository $cronRepository */
        $cronRepository = $repositoryService->create(CronRepository::class);

        switch ($input->getOption('type')) {
            case 'exception':
                $crons = $cronRepository->getByStatus(CronEntity::STATUS_SUSPENDED_EXCEPTION);
                break;
            case 'suspended':
                $crons = $cronRepository->getByStatus(CronEntity::STATUS_SUSPENDED);
                break;
            case 'pending':
                $crons = $cronRepository->getByStatus(CronEntity::STATUS_PENDING);
                break;
            case 'running':
                $crons = $cronRepository->getByStatus(CronEntity::STATUS_RUNNING);
                break;
            default:
                $crons = $cronRepository->getAll();
                break;
        }

        foreach ($crons as $cron) {
            $cronEntity = new CronEntity($cron);
            $output->writeln((string)$cronEntity->showCommand());
        }
    }

}
