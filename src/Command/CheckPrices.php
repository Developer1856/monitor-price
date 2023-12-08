<?php

namespace App\Command;


use App\Services\MonitorPricesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-prices',
    description: 'Сheck prices and send updates',
    hidden: false
)]
class CheckPrices extends Command
{
    private MonitorPricesService $monitorPricesService;

    public function __construct(MonitorPricesService $monitorPricesService)
    {
        parent::__construct();
        $this->monitorPricesService = $monitorPricesService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->monitorPricesService->execute();

        return Command::SUCCESS;
    }

}