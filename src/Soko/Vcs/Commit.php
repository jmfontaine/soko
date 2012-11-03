<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs;

use Soko\Vcs\Driver\DriverInterface;

class Commit
{
    protected $driver;

    protected $hash;

    public function __construct(DriverInterface $driver, $hash)
    {
        $this->setDriver($driver)
             ->setHash($hash);
    }

    public function exportFullContentToDir($dir)
    {
        $this->driver->exportFullContentToDir($dir, $this->getHash());
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }
}
