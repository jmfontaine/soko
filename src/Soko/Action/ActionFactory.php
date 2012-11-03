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

class ActionFactory
{
    public static function createAction($name, $parameters)
    {
        $normalizedName = str_replace('_', ' ', $name);
        $normalizedName = ucwords($normalizedName);
        $normalizedName = str_replace(' ', '', $normalizedName);
        $class = '\\Soko\\Action\\' . $normalizedName;

        $action = new $class($parameters);

        return $action;
    }
}
