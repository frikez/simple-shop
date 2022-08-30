<?php

namespace App\Shop\Infrastructure\Cli;


use App\Shop\Application\Command\Product\CreateProductCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateNewProductCommand extends Command
{
    use HandleTrait;
    protected static $defaultName = 'product:create';

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question('Please enter product name : ');
        $productName = (string) $helper->ask($input, $output, $question);

        if ($productName === '') {
            $output->writeln('<error>Product name should be not blank</error>');
        }


        $question = new Question('Please enter product description : ');
        $productDescription = (string) $helper->ask($input, $output, $question);

        if (strlen($productDescription) < 10) {
            $output->writeln('<error>Product description should be greater than 10 characters</error>');
        }

        $question = new Question('Please enter product price : ');
        $productPrice = (string) $helper->ask($input, $output, $question);

        if ($productPrice === '') {
            $output->writeln('<error>Product price should be not blank</error>');
        }


        $productId = $this->handle(new CreateProductCommand($productName, $productDescription, floatval($productPrice)));

        $output->writeln(sprintf('<info>Product created with id: %s</info>', $productId) );

        return 0;
    }
}
