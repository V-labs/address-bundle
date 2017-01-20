<?php

namespace Vlabs\AddressBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class VlabsAddressExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if(isset($config['geocoder']))
        {
            $geocoder = $container->getDefinition('vlabs_address.service.geocoder');

            if(isset($config['geocoder']['google_api_key'])){
                $geocoder->replaceArgument(0, $config['geocoder']['google_api_key']);
            }
            if(isset($config['geocoder']['google_geocoder_base_url'])){
                $geocoder->replaceArgument(1, $config['geocoder']['google_geocoder_base_url']);
            }
        }
    }
}
