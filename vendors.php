<?php

set_time_limit(0);

if (!is_dir($vendorDir = __DIR__.'/vendor')) {
    mkdir($vendorDir, 0777, true);
}

$deps = array(
    array('symfony', 'git://github.com/symfony/symfony', isset($_SERVER['SYMFONY_VERSION']) ? $_SERVER['SYMFONY_VERSION'] : 'origin/master'),
    array('twig', 'git://github.com/fabpot/Twig', 'origin/master'),
    array('molino', 'git://github.com/pablodip/molino', 'origin/master'),
    array('mondator', 'git://github.com/mandango/mondator', 'origin/master'),
    array('mandango', 'git://github.com/mandango/mandango', 'origin/master'),
    array('doctrine-common', 'git://github.com/doctrine/common', 'origin/master'),
    array('doctrine-dbal', 'git://github.com/doctrine/dbal', 'origin/master'),
    array('doctrine-orm', 'git://github.com/doctrine/doctrine2', 'origin/master'),
    array('doctrine-mongodb', 'git://github.com/doctrine/mongodb', 'origin/master'),
    array('doctrine-mongodb-odm', 'git://github.com/doctrine/mongodb-odm', 'origin/master'),
    array('metadata', 'git://github.com/schmittjoh/metadata', 'origin/master'),
    array('mandango-bundle', 'git://github.com/mandango/MandangoBundle', 'origin/master', 'bundles/Mandango/MandangoBundle'),
    array('doctrine-bundle', 'git://github.com/doctrine/DoctrineBundle', 'origin/master', 'bundles/Doctrine/Bundle/DoctrineBundle'),
    array('pablodip-module-bundle', 'git://github.com/pablodip/PablodipModuleBundle', 'origin/master', 'bundles/Pablodip/ModuleBundle'),
);

foreach ($deps as $dep) {
    if (3 === count($dep)) {
        list($name, $url, $rev) = $dep;
        $target = null;
    } else {
        list($name, $url, $rev, $target) = $dep;
    }

    if (null !== $target) {
        $installDir = $vendorDir.'/'.$target;
    } else {
        $installDir = $vendorDir.'/'.$name;
    }

    $install = false;
    if (!is_dir($installDir)) {
        $install = true;
        echo "> Installing $name\n";

        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    if (!$install) {
        echo "> Updating $name\n";
    }

    system(sprintf('cd %s && git fetch origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
