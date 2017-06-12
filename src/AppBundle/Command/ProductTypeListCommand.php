<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductTypeListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:productType:list')
            ->setDescription('list productTypes')
            ->setHelp("Displays productTypes")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $productTypeRepository = $em->getRepository('AppBundle:ProductType');
        $productTypes = $productTypeRepository->findAll();

        $output->writeln([
            '<info>Product Types</info>',
            '<info>============</info>',
        ]);
        foreach ($productTypes as $type) {
            $output->writeln('<info>* '.$type->getName().'</info>');
        }
    }

}