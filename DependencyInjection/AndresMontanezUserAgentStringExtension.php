<?php
/*
* This file is part of the UserAgentString bundle.
*
* (c) Andrés Montañez <andres@andresmontanez.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace AndresMontanez\UserAgentStringBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Bundle Configuration Loader
 *
 * @author Andrés Montañez <andres@andresmontanez.com>
 */
class AndresMontanezUserAgentStringExtension extends Extension
{
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\DependencyInjection\Extension\ExtensionInterface::load()
	 */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('andres_montanez_user_agent_string.source_file', $config['source']);
        $container->setParameter('andres_montanez_user_agent_string.parse_robots', $config['robots']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
