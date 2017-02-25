<?php

namespace WebCanyon\WCronBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Container;
use WebCanyon\WCronBundle\Repository\InstallRepository;
use WebCanyon\WCronBundle\Repository\RepositoryService;

/**
 * Class WCronInstallCommand
 *
 * @package WebCanyon\WCronBundle\Command
 */
class WCronInstallCommand extends ContainerAwareCommand
{
    /** @var Container $container */
    protected $container;

    /** @var InstallRepository $installRepository */
    protected $installRepository;

    /** @var string $filenameRunWcron */
    protected $filenameRunWcron = 'wcron';

    /**
     * Method configure
     */
    protected function configure()
    {
        $this
            ->setName('wcron:install')
            ->addArgument(
                'parts',
                InputArgument::REQUIRED,
                "all - install all needed\n
                symlink - create symlink to /usr/local/bin/{$this->filenameRunWcron}
                permissions - fix run permission\n
                databases - install both databases\n
                ")
            ->setDescription('Run this command to generate database tables and make all initial settings required.')
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
        //init container
        $this->container = $this->getContainer();

        switch ($input->getArgument('parts')) {
            case 'all':
                //fix permissions
                $this->fixPermissions($output);
                //link bash script
                $this->linkBashScript($output);
                //init repositories
                $this->initRepositories();
                //check if installed already and confirm reinstall
                $this->installDatabases($input, $output);
                break;
            case 'permissions':
                //fix permissions
                $this->fixPermissions($output);
                break;
            case 'symlink':
                //fix permissions
                $this->linkBashScript($output);
                break;
            case 'databases':
                //init repositories
                $this->initRepositories();
                //check if installed already and confirm reinstall
                $this->installDatabases($input, $output);
                break;
            default:
                throw new InvalidArgumentException('Invalid value for argument "parts".');
        }
    }

    /**
     * Method init
     */
    protected function initRepositories()
    {
        /** @var RepositoryService $repositoryService */
        $repositoryService = $this->container->get('wcron.repository');
        /** @var InstallRepository $userRepository */
        $this->installRepository = $repositoryService->create(InstallRepository::class);
        $this->installRepository->setDatabaseName($this->container->getParameter('database_name'));
    }

    /**
     * Method fixPermissions
     *
     * @param OutputInterface $output
     */
    protected function fixPermissions(OutputInterface $output)
    {
        $output->writeln(shell_exec("chmod +x ".__DIR__.DIRECTORY_SEPARATOR.$this->filenameRunWcron));
    }

    /**
     * Method linkBashScript
     *
     * @param OutputInterface $output
     */
    protected function linkBashScript(OutputInterface $output)
    {
        $wcron = __DIR__.DIRECTORY_SEPARATOR.$this->filenameRunWcron;
        $link = "/usr/local/bin/".$this->filenameRunWcron;

        $output->writeln(shell_exec('whoami'));
        $output->writeln(shell_exec("sudo ln -sf $wcron $link"));
    }

    /**
     * Method install
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function installDatabases(InputInterface $input, OutputInterface $output)
    {
        if ($this->installRepository->isInstalled()) {
            $output->writeln('Cron manager database is already installed.');
            $output->writeln('If you choose to reinstall it you will lose all settings done before.');
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $confirmation = new ConfirmationQuestion("Are you sure you want to reinstall it? [y/n] \n", false);
            if (!$helper->ask($input, $output, $confirmation))
                return;

            $this->installRepository->uninstall();
            $output->writeln('Uninstallation DONE!');
        }

        $this->installRepository->install();
        $output->writeln('Installation DONE!');
    }

}
