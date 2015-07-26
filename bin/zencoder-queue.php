<?php

use Pekkis\Queue\Processor\ConsoleOutputSubscriber as ProcessorConsoleOutputSubscriber;
use Pekkis\Queue\Processor\Processor;
use Pekkis\Queue\SymfonyBridge\ConsoleOutputSubscriber;
use Symfony\Component\Console\Output\ConsoleOutput;
use Xi\Filelib\Asynchrony\Queue\FilelibMessageHandler;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/../async-common.php';
require_once __DIR__ . '/../zencoder-common.php';

$output = new ConsoleOutput();
$queueSubscriber = new ConsoleOutputSubscriber($output);
$processorSubscriber = new ProcessorConsoleOutputSubscriber($output);

$queue = $pekkisQueueStrategy->getQueue();
$queue->addSubscriber($queueSubscriber);
$queue->addSubscriber($processorSubscriber);

$processor = new Processor($queue);

$messageHandler = new FilelibMessageHandler();
$processor->registerHandler($messageHandler);

try {

    do {
        $ret = $processor->process();

    } while ($ret);

} catch (\Exception $e) {
    $output->writeln(sprintf("CRITICAL: %s", $e->getMessage()));
}


