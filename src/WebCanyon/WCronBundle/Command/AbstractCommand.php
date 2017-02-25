<?php

namespace WebCanyon\WCronBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\OutputInterface;


class AbstractCommand extends ContainerAwareCommand
{
    /** @var array $costomStyles */
    protected $costomStyles;

    /**
     * AbstractCommand constructor.
     *
     * @param null|string $name
     */
    public function __construct($name = 'abstract')
    {
        parent::__construct($name);
    }

    /**
     *  Method addStyles
     *
     * @param OutputInterface $output
     */
    protected function addStyles(OutputInterface &$output)
    {
        //rewrite default console style
        $this->addStyleComment();

        $this->addStyleDefault();
        $this->addStyleNormal();
        $this->addStyleWarning();
        $this->addStyleRunning();
        $this->addStylePending();
        $this->addStyleExpression();

        //colors
        $this->addStyleRed();
        $this->addStyleMagenta();
        $this->addStyleGreen();

        $output->setFormatter(new OutputFormatter(true, $this->costomStyles));
    }

    /**
     * Method addStyleComment
     */
    protected function addStyleComment()
    {
        $this->costomStyles['comment'] = new OutputFormatterStyle('yellow', 'black', []);
    }

    /**
     * Method addStyleDefault
     */
    protected function addStyleDefault()
    {
        $this->costomStyles['default'] = new OutputFormatterStyle('default', 'default', ['default']);
    }

    /**
     * Method addStyleNormal
     */
    protected function addStyleNormal()
    {
        $this->costomStyles['normal'] = new OutputFormatterStyle('white', 'black', []);
    }

    /**
     * Method addStyleWarning
     */
    protected function addStyleWarning()
    {
        $this->costomStyles['warning'] = new OutputFormatterStyle('magenta', 'black', array('bold', 'blink'));
    }

    /**
     * Method addStyleRunning
     */
    protected function addStyleRunning()
    {
        $this->costomStyles['running'] =  new OutputFormatterStyle('black', 'yellow', array('bold'));
    }

    /**
     * Method addStylePending
     */
    protected function addStylePending()
    {
        $this->costomStyles['pending'] = new OutputFormatterStyle('lightGrey', 'black', array('bold'));
    }

    /**
     * Method addStyleExpression
     */
    protected function addStyleExpression()
    {
        $this->costomStyles['expression'] = new OutputFormatterStyle('magenta', 'black', array('bold'));
    }

    /**
     * Method addStyleRed
     */
    protected function addStyleRed()
    {
        $this->costomStyles['red'] = new OutputFormatterStyle('red', 'black', array('bold'));
    }
    /**
     * Method addStyleMagenta
     */
    protected function addStyleMagenta()
    {
        $this->costomStyles['magenta'] = new OutputFormatterStyle('magenta', 'black', array('bold'));
    }
    /**
     * Method addStyleGreen
     */
    protected function addStyleGreen()
    {
        $this->costomStyles['green'] = new OutputFormatterStyle('green', 'black', array('bold'));
    }
}