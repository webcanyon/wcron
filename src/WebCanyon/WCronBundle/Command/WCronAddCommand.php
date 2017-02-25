<?php

namespace WebCanyon\WCronBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Repository\CronRepository;
use WebCanyon\WCronBundle\Repository\RepositoryService;

/**
 * Class CronmanagerAddCommand
 *
 * @package WebCanyon\WCronBundle\Command
 */
class WCronAddCommand extends ContainerAwareCommand
{
    /**
     * Method configure
     */
    protected function configure()
    {
        $this
            ->setName('wcron:add')
            ->setDescription('Add new cron using command line')
            ->addArgument('cron', InputArgument::REQUIRED, 'Cron job line to be added. Ex.: \'#suspended: 1/10 2 * * * wcron:install 2>&1 #mutexed This is a comment\'')
        ;
    }

    /**
     * Method execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cron = $input->getArgument('cron');
        /** @var RepositoryService $repositoryService */
        $repositoryService = $this->getContainer()->get('wcron.repository');
        /** @var CronRepository $cronRepository */
        $cronRepository = $repositoryService->create(CronRepository::class);
        $cron = CronEntity::createFromString($cron);
        $cronRepository->submit($cron);
        $output->writeln("Cron '$cron' was added into database.");
    }
}
