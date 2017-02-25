<?php

namespace WebCanyon\WCronBundle\Command;

use Cron\CronExpression;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Event\CronRunEvent;
use WebCanyon\WCronBundle\Repository\CronRepository;
use WebCanyon\WCronBundle\Repository\RepositoryService;

/**
 * Class WCronRunCommand
 *
 * @package WebCanyon\WCronBundle\Command
 */
class WCronRunCommand extends AbstractCommand
{

    /**
     * Method configure
     */
    protected function configure()
    {
        $this
            ->setName('wcron:run')
            ->setDescription('Command which will run all other crons')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * Method execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Add custom styles
        $this->addStyles($output);

        /** @var RepositoryService $repositoryService */
        $repositoryService = $this->getContainer()->get('wcron.repository');
        /** @var CronRepository $cronRepository */
        $cronRepository = $repositoryService->create(CronRepository::class);

        $crons = $cronRepository->getToRun();


        if (!$crons || count($crons) < 1) {
            $output->writeln('<info>There is no crons to run at this moment.</info>');
            return null;
        }

        $eventDispatcher = $this->getContainer()->get('event_dispatcher');

        foreach ($crons as $cron) {
            $cronEntity = new CronEntity($cron);
            $cronToRun = CronExpression::factory($cronEntity->getExpression());

            $isConsole = true;
            try {
                $this->getApplication()->find($cronEntity->getCommand());
            } catch (CommandNotFoundException $commandNotFoundException) {
                $isConsole = false;
            }

            if ($cronToRun->isDue()) {
                $output->writeln("<info>Running</info> {$cronEntity->showCommand()}");
                $eventDispatcher->dispatch(
                    'wcron.event.cron.run',
                    new CronRunEvent(
                        $cronEntity,
                        $cronRepository,
                        $isConsole
                    )
                );
            }
        }

        $output->writeln('<info>DONE!</info>');

        return true;
    }
}
