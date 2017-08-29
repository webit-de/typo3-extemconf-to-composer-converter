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
$vendor = ($argv[2] ?? 'your-vendorname');
$namespace = $vendor . '\\' . preg_replace('/[^A-Za-z0-9]/', '', ucwords($_EXTKEY, '-_ ')) . '\\';

$composer = [
    'name' => strtolower($vendor) . '/' . $_EXTKEY,
    'description' => $emconf['title'] . ($emconf['description']? ' – ' . $emconf['description'] : ''),
    'license' => 'GPL-2.0+',
    'type' => 'typo3-cms-extension',
    'require' => [

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