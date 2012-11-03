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
namespace Soko\Action;

use Soko\Vcs\Commit;

abstract class AbstractAction implements ActionInterface
{
    private $parameters = array();

    public function __construct(array $parameters)
    {
        $this->setParameters($parameters);
    }

    public function getParameter($name)
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new \InvalidArgumentException("Invalid parameter ($name)");
        }

        return $this->parameters[$name];
    }

    public function getType()
    {
        $parts = explode('\\', get_class($this));
        $count = count($parts);

        return $parts[$count - 1];
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
