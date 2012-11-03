<?php
/**
 * @package Soko
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace Soko\Vcs;

use Soko\Vcs\Driver\DriverInterface;

/**
 * This class represents a commit.
 */
class Commit
{
    /**
     * VCS driver
     *
     * @var \Soko\Vcs\Driver\DriverInterface
     */
    protected $driver;

    /**
     * Hash of the commit
     *
     * @var string
     */
    protected $hash;

    /**
     * Class constructor
     *
     * @param \Soko\Vcs\Driver\DriverInterface $driver VCS driver
     * @param string                           $hash   Hash of the commit
     *
     * return void
     */
    public function __construct(DriverInterface $driver, $hash)
    {
        $this->setDriver($driver)
             ->setHash($hash);
    }

    /**
     * Exports the full content of the commit into a directory
     *
     * @param string $dir Directory into which the content should be exported
     *
     * @return \Soko\Vcs\Commit The current instance of this class to allow method call chaining
     */
    public function exportFullContentToDir($dir)
    {
        $this->driver->exportFullContentToDir($dir, $this->getHash());

        return $this;
    }

    /**
     * Returns the hash of the commit
     *
     * @return string Hash of the commit
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Defines the VCS driver to use to access to the repository
     *
     * @param Driver\DriverInterface $driver VCS driver
     *
     * @return \Soko\Vcs\Commit The current instance of this class to allow method call chaining
     */
    protected function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Defines the hash of the commit
     *
     * @param $hash Hash of the commit
     *
     * @return \Soko\Vcs\Commit The current instance of this class to allow method call chaining
     */
    protected function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }
}
