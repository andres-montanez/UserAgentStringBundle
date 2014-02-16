<?php
/*
* This file is part of the UserAgentString bundle.
*
* (c) Andrés Montañez <andres@andresmontanez.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace AndresMontanez\UserAgentStringBundle\CacheWarmer;

use AndresMontanez\UserAgentStringBundle\Service\UserAgentLoaderService;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Cache Warmer
 *
 * @author Andrés Montañez <andres@andresmontanez.com>
 */
class UserAgentLoaderWarmer implements CacheWarmerInterface
{
    /**
     * UserAgent Definitions Loader
     * @var \AndresMontanez\UserAgentStringBundle\Service\UserAgentLoaderService
     */
    protected $loader;

    /**
     * Path to the file with the UserAgent Definitions
     * @var string
     */
    protected $sourceFile;

    /**
     * Parse or not for Robots
     * @var boolean
     */
    protected $parseRobots;

    /**
     * Constructor
     * @param UserAgentLoaderService $loader
     * @param string                 $sourceFile
     * @param boolean                $parseRobots
     */
    public function __construct(UserAgentLoaderService $loader, $sourceFile, $parseRobots = false)
    {
        $this->loader = $loader;
        $this->sourceFile = $sourceFile;
        $this->parseRobots = $parseRobots;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface::warmUp()
     */
    public function warmUp($cacheDir)
    {
        $this->loader->load($this->sourceFile, $this->parseRobots);
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface::isOptional()
     */
    public function isOptional()
    {
        return true;
    }
}
