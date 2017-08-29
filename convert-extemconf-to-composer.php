<?php
/**
 * Convert a TYPO3 extension declaration file (ext_emconf.php) to a
 * Composer file (composer.json)
 *
 *   php convert-extemconf-to-composer.php /path/to/extension/ > /path/to/extension/composer.json
 */

if(empty($argv[1])) {
    die('Missing argument');
}
if(false === is_dir($argv[1])) {
    die('No valid path');
}

$_EXTKEY = basename($argv[1]);
require($argv[1] . 'ext_emconf.php');
$emconf = $EM_CONF[$_EXTKEY];

$vendor = ucwords($argv[2] ?? 'Your-Vendorname', '-_ ') ;
$package = preg_replace('/[_ ]/', '-', strtolower($vendor . '/' . $_EXTKEY));
$namespace = $vendor . '\\' . preg_replace('/[^A-Za-z0-9]/', '', ucwords($_EXTKEY, '-_ ')) . '\\';

$composer = [
    'name' => $package,
    'description' => $emconf['title'] . ($emconf['description']? ' – ' . $emconf['description'] : ''),
    'license' => 'GPL-2.0+',
    'type' => 'typo3-cms-extension',
    'require' => [
        'typo3/cms-core' => '^7.6 || ^8.7'
    ],
    'autoload' => [
        'psr-4' => [$namespace => 'Classes/']
    ],
    'replace' => [
        $_EXTKEY => 'self.version',
        'typo3-ter/' . $_EXTKEY => 'self.version'
    ]
];
$output = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

echo $output . PHP_EOL;
exit(1);
