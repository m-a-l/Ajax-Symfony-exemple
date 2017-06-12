<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class HelloYouCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:sayhello')
            ->setDescription('Says hello. Useless.')
            ->setHelp("Give your name and appreciate your polite app saying you hello")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('<fg=yellow>What\'s your name ? [Idiot]</> : ', 'Idiot');
        $name = $helper->ask($input, $output, $question);
        $output->writeln('<info>Hello '.$name.' !</info>');
    }


}