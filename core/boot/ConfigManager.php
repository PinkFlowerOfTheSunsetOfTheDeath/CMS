<?php
namespace App\Boot;
use App\Entities\Config;
use App\Helpers\Database;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigManager
 * @package App\Boot
 */
class ConfigManager
{
    /**
     * Path to configuration directory
     */
    const CONFIGURATION__DIR = __DIR__ . '/../config';

    /**
     * Path to configuration file
     */
    const CONFIGURATION__FILE = self::CONFIGURATION__DIR . '/config.yaml';


    /**
     * Load configuration file, parse it and set up the different components (DB)
     */
    public function loadConfiguration()
    {
        // TODO: Manage errors
        $config = YAML::parse(file_get_contents(self::CONFIGURATION__FILE));
        return $config;
    }

    /**
     * Load Configuration Routes
     * @return RouteCollection
     */
    public function loadRoutes(): RouteCollection
    {
        $fileLocator = new FileLocator([self::CONFIGURATION__DIR]);
        $loader = new YamlFileLoader($fileLocator);
        $routes = $loader->load('routing.yaml');
        return $routes;
    }

    /**
     * Configure Website - Database Access
     * @param array $config - Array of configurations
     */
    public function configureWebsite(array $config)
    {
        // Configure database access
        Database::configureDB($config['database']);

        // Configure Theme
        ThemeManager::setTheme($config['website']['theme']);
    }

    /**
     * @param RouteCollection $config - Config
     */
    public function configureRouter(RouteCollection $config)
    {
        // Configure Router
        Router::configureRouter($config);
    }

    public function saveConfig(array $config)
    {
        // Create YAML from config array
        $yamlConf = Yaml::dump($config);
        // save config to file
        file_put_contents(self::CONFIGURATION__FILE, $yamlConf);
    }

    /**
     * Update Website's active theme
     * @param string $theme - theme name to set in config
     */
    public function updateActiveTheme(string $theme)
    {
        $config = $this->loadConfiguration();

        $config['website']['theme'] = $theme;

        $this->saveConfig($config);
    }

    /**
     * @param Config $conf
     * @param bool $init
     * @return array
     */
    public function updateConfig(Config $conf, bool $init = true)
    {
        $config = [
            'database' => [
                'name' => $conf->name,
                'user' => $conf->user,
                'password' => $conf->password,
                'host' => $conf->host,
                'port' => $conf->port,
            ],
            'website' => [
                'initialized' => true,
                'name' => $conf->web,
                'theme' => 'default'
            ]
        ];

        $this->saveConfig($config);
        return $config;
    }
}