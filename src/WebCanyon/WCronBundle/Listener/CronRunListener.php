<?php

namespace WebCanyon\WCronBundle\Listener;


use DateTime;
use Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Entity\CronRunEntity;
use WebCanyon\WCronBundle\Event\CronRunEvent;
use WebCanyon\WCronBundle\Repository\CronRepository;
use WebCanyon\WCronBundle\Repository\CronRunRepository;
use WebCanyon\WCronBundle\Repository\RepositoryService;

/**
 * Class CronRunListener
 *
 * @package WebCanyon\WCronBundle\Listener
 */
class CronRunListener
{
    /** @var Container $container */
    protected $container;

    /** @var RepositoryService $repositoryService */
    protected $repositoryService;

    /** @var CronRepository $cronRepository */
    protected $cronRepository;

    /** @var CronRunRepository $cronRunRepository */
    protected $cronRunRepository;

    /**
     * CronRunListener constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->repositoryService = $this->container->get('wcron.repository');
        $this->cronRepository = $this->repositoryService->create(CronRepository::class);
        $this->cronRunRepository = $this->repositoryService->create(CronRunRepository::class);
    }

    /**
     * Method run
     *
     * @param CronRunEvent $cronRunEvent
     *
     * @throws Exception
     */
    public function run(CronRunEvent $cronRunEvent)
    {
        try {
            //init runtime
            $runTime = new DateTime();

            // get commands root dir
            $rootDir = realpath($this->container->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . "..");

            //set console dir if command is a symfony console command
            $command = "{$cronRunEvent->getCron()->getCommand()}";
            if ($cronRunEvent->isConsole()) {
                $consoleDir = $rootDir . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR . "console";

                //command to run
                $command = "php $consoleDir {$cronRunEvent->getCron()->getCommand()} ";
            }

            $process = new Process($command);

            //flag running process to DB
            $cronRunEvent->getCron()->setStatus(1);
            $cronRunEvent->getCronRepository()->submit($cronRunEvent->getCron());

            //run process
            $process->mustRun();

            //save process to database
            $this->saveCronRunToDb($cronRunEvent, $process, $runTime);


        } catch (ProcessFailedException $exception) {
            $this->saveCronRunToDb($cronRunEvent, $process, $runTime, $exception);
        } catch (Exception $exception) {
            $this->saveOtherExceptions($cronRunEvent, $runTime, $exception);
            throw $exception;
        }
    }

    /**
     * Method saveCronRunToDb
     *
     * @param CronRunEvent $cronRunEvent
     * @param Process $process
     * @param DateTime $runTime
     * @param ProcessFailedException $exception
     */
    protected function saveCronRunToDb(CronRunEvent $cronRunEvent, Process $process, DateTime $runTime, ProcessFailedException $exception = null) {
        /** @var CronRunEntity $cronRunEntity */
        $cronRunEntity = new CronRunEntity();
        $cronRunEntity->setCronId($cronRunEvent->getCron()->getId());
        $cronRunEntity->setRunTime($runTime);
        $cronRunEntity->setComment($cronRunEvent->getCron()->getComment());
        if ($exception) {
            //set output
            $cronRunEntity->setOutput($exception->getMessage());

            //set statusses
            $cronRunEvent->getCron()->setStatus(CronEntity::STATUS_SUSPENDED_EXCEPTION);
            $cronRunEntity->setStatus(CronRunEntity::STATUS_FAIL);
        } else {
            //set output
            $cronRunEntity->setOutput($process->getOutput());

            //set statusses
            $cronRunEvent->getCron()->setStatus(CronEntity::STATUS_PENDING);
            $cronRunEntity->setStatus(CronRunEntity::STATUS_SUCCESS);
        }

        $this->cronRepository->submit($cronRunEvent->getCron());
        $this->cronRunRepository->submit($cronRunEntity);
    }

    /**
     *
     *
     * @param CronRunEvent $cronRunEvent
     * @param DateTime $runTime
     * @param Exception $exception
     *
     */
    protected function saveOtherExceptions(CronRunEvent $cronRunEvent, DateTime $runTime, Exception $exception)
    {
        /** @var CronRunEntity $cronRunEntity */
        $cronRunEntity = new CronRunEntity();
        $cronRunEntity->setCronId($cronRunEvent->getCron()->getId());
        $cronRunEntity->setRunTime($runTime);
        $cronRunEntity->setComment($cronRunEvent->getCron()->getComment());

        //set output
        $cronRunEntity->setOutput($exception->getMessage());

        //set statusses
        $cronRunEvent->getCron()->setStatus(CronEntity::STATUS_SUSPENDED_EXCEPTION);
        $cronRunEntity->setStatus(CronRunEntity::STATUS_FAIL);
    }
}