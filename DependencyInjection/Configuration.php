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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Bundle Configuration
 *
 * @author Andrés Montañez <andres@andresmontanez.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Config\Definition\ConfigurationInterface::getConfigTreeBuilder()
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('andres_montanez_user_agent_string');

        $rootNode
            ->children()
            ->scalarNode('source')->defaultValue(__DIR__ . '/../Resources/metadata/uas.xml')->end()
            ->booleanNode('robots')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
}
