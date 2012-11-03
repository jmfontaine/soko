<?php
/**
 * Copyright (c) 2012, Jean-Marc Fontaine
 * All rights reserved.
 *
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
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
