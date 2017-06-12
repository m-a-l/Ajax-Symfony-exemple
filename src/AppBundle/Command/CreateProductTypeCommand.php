<?php
namespace AppBundle\Command;

use AppBundle\Entity\ProductType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateProductTypeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:productType:add')
            ->setDescription('Allows tou to create a new ProductType')
            ->setHelp("Give the parameters and let the app create a new ProductType")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('<fg=yellow>ProductType Name</> : ');
        $name = $helper->ask($input, $output, $question);
        $productType = new ProductType();
        $productType->setName($name);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($productType);
        $em->flush();

        $output->writeln('<info>ProductType'.$name.'successfully generated!</info>');
    }

}