<?php

namespace ProductBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use ProductBundle\Service\CategoryCreator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCategoryCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:create-category';

    protected function configure()
    {
        $this
            ->setDescription('creates a category')
            ->setHelp('The command allows you to create a category')
            ->addArgument('title', InputArgument::REQUIRED, 'The category title')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'creating the category with title '.$input->getArgument('title'),
            '...',
        ]);

        $this->getContainer()->get(CategoryCreator::class)->createCategory($input->getArgument('title'));

        $output->writeln('Category created successfully!');
    }
}