<?php
namespace Soko\Renderer;

use Soko\Report;

class Stream
{
    public function render(Report $report)
    {
        foreach ($report->getData() as $actionData) {
            $action = $actionData['action'];

            echo sprintf(
                '%s: %s' . PHP_EOL,
                $action->getType(),
                $actionData['isSuccess'] ? 'Ok' : 'Error'
            );

            if (false === $actionData['isSuccess']) {
                echo $$actionData['output'] . PHP_EOL;
            }
        }
    }
}
