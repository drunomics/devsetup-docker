<?php

/**
 * @file
 * Lagoon Drupal 8+ configuration file.
 *
 * You should not edit this file, please use environment specific files!
 * @see https://github.com/amazeeio/drupal-integrations/blob/master/assets/settings.lagoon.php
 */
 
// Lagoon version.
if (!defined("LAGOON_VERSION")) {
  define("LAGOON_VERSION", "1");
}

// Lagoon database connection.
if(getenv('LAGOON')){
  $databases['default']['default'] = array(
    'driver' => 'mysql',
    'database' => getenv('MARIADB_DATABASE') ?: 'drupal',
    'username' => getenv('MARIADB_USERNAME') ?: 'drupal',
    'password' => getenv('MARIADB_PASSWORD') ?: 'drupal',
    'host' => getenv('MARIADB_HOST') ?: 'mariadb',
    'port' => 3306,
    'prefix' => '',
  );
}

// Lagoon reverse proxy settings.
if (getenv('LAGOON')) {
  $settings['reverse_proxy'] = TRUE;
}

// Trusted Host Patterns.
// Trusted host patterns are not necessary on lagoon as traffic will only
// be routed to your site via the routes (hosts) defined in .lagoon.yml.
if (getenv('LAGOON')) {
  $settings['trusted_host_patterns'][] = '.*';
}

// Temp directory.
if (getenv('TMP')) {
  $config['system.file']['path']['temporary'] = getenv('TMP');
}

// Hash salt.
// Use HASH_SALT if found in the current environment otherwise fallback on
// MARIADB_HOST which is a randomly generated service name.
if (getenv('LAGOON')) {
  $settings['hash_salt'] = hash('sha256', getenv('HASH_SALT') ?: getenv('MARIADB_HOST'));
}


// Lagoon Solr connection.
// WARNING: you have to create a search_api server having "solr" machine name at
// /admin/config/search/search-api/add-server to make this work.
if (getenv('LAGOON')) {
  $config['search_api.server.solr']['backend_config']['connector_config']['host'] = getenv('SOLR_HOST') ?: 'solr';
  $config['search_api.server.solr']['backend_config']['connector_config']['path'] = '/solr/';
  $config['search_api.server.solr']['backend_config']['connector_config']['core'] = getenv('SOLR_CORE') ?: 'drupal';
  $config['search_api.server.solr']['backend_config']['connector_config']['port'] = 8983;
  $config['search_api.server.solr']['backend_config']['connector_config']['http_user'] = (getenv('SOLR_USER') ?: '');
  $config['search_api.server.solr']['backend_config']['connector_config']['http']['http_user'] = (getenv('SOLR_USER') ?: '');
  $config['search_api.server.solr']['backend_config']['connector_config']['http_pass'] = (getenv('SOLR_PASSWORD') ?: '');
  $config['search_api.server.solr']['backend_config']['connector_config']['http']['http_pass'] = (getenv('SOLR_PASSWORD') ?: '');
  $config['search_api.server.solr']['name'] = 'Lagoon Solr - Environment: ' . getenv('LAGOON_PROJECT');
}

// Lagoon Redis connection.
if (getenv('LAGOON')) {
  $settings['redis.connection']['interface'] = 'PhpRedis';
  $settings['redis.connection']['host'] = getenv('REDIS_HOST') ?: 'redis';
  $settings['redis.connection']['port'] = 6379;

  $settings['cache_prefix']['default'] = getenv('LAGOON_PROJECT') . '_' . getenv('LAGOON_GIT_SAFE_BRANCH');

  // Do not set the cache during installations of Drupal.
  if (!drupal_installation_attempted() && extension_loaded('redis') && file_exists(__DIR__ . '/../../modules/contrib/redis/redis.services.yml')) {
    $settings['cache']['default'] = 'cache.backend.redis';

    // Include the default example.services.yml from the module, which will
    // replace all supported backend services (that currently includes the cache tags
    // checksum service and the lock backends, check the file for the current list).
    $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

    // Allow the services to work before the Redis module itself is enabled.
    $settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';

    // Manually add the classloader path, this is required for the container cache bin definition below
    // and allows to use it without the redis module being enabled.
    $class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');

    // Use redis for container cache.
    // The container cache is used to load the container definition itself, and
    // thus any configuration stored in the container itself is not available
    // yet. These lines force the container cache to use Redis rather than the
    // default SQL cache.
    $settings['bootstrap_container_definition'] = [
      'parameters' => [],
      'services' => [
        'redis.factory' => [
          'class' => 'Drupal\redis\ClientFactory',
        ],
        'cache.backend.redis' => [
          'class' => 'Drupal\redis\Cache\CacheBackendFactory',
          'arguments' => ['@redis.factory', '@cache_tags_provider.container', '@serialization.phpserialize'],
        ],
        'cache.container' => [
          'class' => '\Drupal\redis\Cache\PhpRedis',
          'factory' => ['@cache.backend.redis', 'get'],
          'arguments' => ['container'],
        ],
        'cache_tags_provider.container' => [
          'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
          'arguments' => ['@redis.factory'],
        ],
        'serialization.phpserialize' => [
          'class' => 'Drupal\Component\Serialization\PhpSerialize',
        ],
      ],
    ];
  }
}

