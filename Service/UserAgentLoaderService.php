<?php
/*
* This file is part of the UserAgentString bundle.
*
* (c) Andrés Montañez <andres@andresmontanez.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace AndresMontanez\UserAgentStringBundle\Service;

use Psr\Log\LoggerInterface;
use DateTime;

/**
 * Service for Loading the UserAgent Definitions
 *
 * @author Andrés Montañez <andres@andresmontanez.com>
 */
class UserAgentLoaderService
{
	/**
	 * The Cache Directory
	 * @var string
	 */
	protected $cacheDir;

	/**
	 * Indicates if Debuging is enabled or not.
	 * In debug mode the definitions are never read from the cache.
	 * @var boolean
	 */
	protected $debug;

	/**
	 * The Logger
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param string $cacheDir
	 * @param boolean $debug
	 * @param LoggerInterface $logger
	 */
    public function __construct($cacheDir, $debug, LoggerInterface $logger)
    {
        $this->cacheDir = $cacheDir;
        $this->debug = $debug;
        $this->logger = $logger;
    }

    /**
     * Load the UserAgent Definitions
     * @param string $filename
     * @param boolean $parseRobots
     * @return array
     */
    public function load($filename, $parseRobots = false)
    {
    	$cacheFile = $this->cacheDir . '/user-agent-strings-' . md5($filename) . '-' . ($parseRobots ? 'with_robots' : 'widthout_robots') . '-cache.php';
    	$data = array(
		    'robots' => array(),
			'operating_systems' => array(),
			'browsers' => array(),
			'browser_types' => array(),
			'browsers_reg' => array(),
			'browsers_os' => array(),
			'os_browsers' => array(),
			'operating_systems_reg' => array(),
			'devices' => array(),
			'devices_reg' => array(),
		);

    	// If we are in a Non Debug environment, use Cached Data
    	if ($this->debug == false && file_exists($cacheFile) && is_readable($cacheFile)) {
    		$cachedData = unserialize(file_get_contents($cacheFile));
    		if ($cachedData) {
    			return $cachedData;
    		}
    	}

        if (file_exists($filename) && is_readable($filename)) {
        	$xml = simplexml_load_file($filename);
        	if ($xml->description->version) {
        		$versionDate = (string) $xml->description->version;
        		$versionDate = new DateTime(substr($versionDate, 0, 4) . '-' . substr($versionDate, 4, 2) . '-' . substr($versionDate, 6, 2));
        		$diff = $versionDate->diff(new \DateTime, true);
        		if ($diff->days > 60) {
        			$this->logger->warning('Your UserAgent String File is more than two months old! Please consider to upgrade it by downloading it from: http://user-agent-string.info/. If you are using the file from the bundle, then update the bundle.');
        		}
        	}

        	// Load Robots
        	if ($parseRobots === true) {
        		$robots = $xml->xpath('/uasdata/data/robots/robot');
        		foreach ($robots as $robot) {
        			$data['robots'][sha1((string) $robot->useragent)] = array(
    					'id' => (string) $robot->id,
    					'useragent' => (string) $robot->useragent,
    					'family' => (string) $robot->family,
    					'name' => (string) $robot->name,
    					'url_company' => (string) $robot->url_company,
    					'icon' => (string) $robot->icon,
    					'bot_info_url' => (string) $robot->bot_info_url,
        			);
        		}
        	}

        	// Load Operating Systems
        	$operatingSystems = $xml->xpath('/uasdata/data/operating_systems/os');
        	foreach ($operatingSystems as $os) {
        		$data['operating_systems'][(string) $os->id] = array(
    				'id' => (string) $os->id,
    				'family' => (string) $os->family,
    				'name' => (string) $os->name,
    				'url' => (string) $os->url,
    				'company' => (string) $os->company,
    				'url_company' => (string) $os->url_company,
    				'icon' => (string) $os->icon,
    				'os_info_url' => (string) $os->os_info_url,
        		);
        	}

        	// Load Browsers
        	$browsers = $xml->xpath('/uasdata/data/browsers/browser');
        	foreach ($browsers as $browser) {
        		$data['browsers'][(string) $browser->id] = array(
    				'id' => (string) $browser->id,
    				'type' => (string) $browser->type,
    				'name' => (string) $browser->name,
    				'url' => (string) $browser->url,
    				'company' => (string) $browser->company,
    				'url_company' => (string) $browser->url_company,
    				'icon' => (string) $browser->icon,
    				'browser_info_url' => (string) $browser->browser_info_url,
        		);
        	}

        	// Load Browser Types
        	$browserTypes = $xml->xpath('/uasdata/data/browser_types/browser_type');
        	foreach ($browserTypes as $browserType) {
        		$data['browser_types'][(string) $browserType->id] = array(
    				'id' => (string) $browserType->id,
    				'type' => (string) $browserType->type,
        		);
        	}

        	// Load Browser RegEx
        	$browserRegExs = $xml->xpath('/uasdata/data/browsers_reg/browser_reg');
        	foreach ($browserRegExs as $browserRegEx) {
        		$data['browsers_reg'][(string) $browserRegEx->order] = array(
    				'order' => (string) $browserRegEx->order,
    				'browser_id' => (string) $browserRegEx->browser_id,
    				'regstring' => (string) $browserRegEx->regstring,
        		);
        	}

        	// Load Browser vs Operating Systems
        	$browserOperatingSystems = $xml->xpath('/uasdata/data/browsers_os/browser_os');
        	foreach ($browserOperatingSystems as $browserOperatingSystem) {
        		$data['browsers_os'][(string) $browserOperatingSystem->browser_id] = (string) $browserRegEx->os_id;
        		$data['os_browsers'][(string) $browserOperatingSystem->os_id] = (string) $browserRegEx->browser_id;
        	}

        	// Load Operating Systems RegEx
        	$operatingSystemsRegEx = $xml->xpath('/uasdata/data/operating_systems_reg/operating_system_reg');
        	foreach ($operatingSystemsRegEx as $operatingSystemRegEx) {
        		$data['operating_systems_reg'][(string) $operatingSystemRegEx->order] = array(
        				'order' => (string) $operatingSystemRegEx->order,
        				'os_id' => (string) $operatingSystemRegEx->os_id,
        				'regstring' => (string) $operatingSystemRegEx->regstring,
        		);
        	}

        	// Load Devices
        	$devices = $xml->xpath('/uasdata/data/devices/device');
        	foreach ($devices as $device) {
        		$data['devices'][(string) $device->id] = array(
    				'id' => (string) $device->id,
    				'name' => (string) $device->name,
    				'icon' => (string) $device->icon,
    				'device_info_url' => (string) $device->device_info_url,
        		);
        	}

        	// Load Devices RegEx
        	$devicesRegEx = $xml->xpath('/uasdata/data/devices_reg/device_reg');
        	foreach ($devicesRegEx as $deviceRegEx) {
        		$data['devices_reg'][(string) $deviceRegEx->order] = array(
        				'order' => (string) $deviceRegEx->order,
        				'device_id' => (string) $deviceRegEx->device_id,
        				'regstring' => (string) $deviceRegEx->regstring,
        		);
        	}

        	// If we are in a Non Debug environment, cache file
        	if ($this->debug == false && is_writable($this->cacheDir)) {
        		file_put_contents($cacheFile, serialize($data));
        	}
        }

        return $data;
    }
}