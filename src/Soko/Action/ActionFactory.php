<?php
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
