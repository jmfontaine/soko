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
namespace Soko;

use Soko\Action\ActionInterface;

/**
 * Report of the actions
 */
class Report
{
    private $data = array();

    private $exitCode = true;

    public function addActionData(ActionInterface $action, $isSuccess, $output)
    {
        $this->data[] = array(
            'action'    => $action,
            'isSuccess' => $isSuccess,
            'output'    => $output,
        );
    }

    public function getData()
    {
        return $this->data;
    }

    public function getOutput()
    {
        $output = '';
        foreach ($this->data as $action) {
            $output .= $action['output'];
        }

        return $output;
    }

    public function isSuccessfull()
    {
        return 0 === $this->getExitCode();
    }

    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    public function getExitCode()
    {
        return $this->exitCode;
    }
}
