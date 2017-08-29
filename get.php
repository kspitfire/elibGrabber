<?php

include_once 'config.php';

$currentOptions = getopt('s:', ['mode:', 'total:', 'id::', 'start::', 'path::', 'keep::']);

// Initializing options
if (empty($currentOptions['s'])) {
    echo sprintf("Please, provide a name for output file. %sUse `-s` option%s", PHP_EOL, PHP_EOL);
    die();
} else {
    $outputFileName = $currentOptions['s'];
}

if (empty($currentOptions['total'])) {
    echo sprintf("You need to point total pages of requested document. %sUse `--total` option%s", PHP_EOL, PHP_EOL);
    die();
} else {
    $totalPages = $currentOptions['total'];
}

if (empty($currentOptions['mode'])) {
    echo sprintf("You need to choose mode for downloading.%sUse `--mode` option.%sAll modes defined at config.php.%s", PHP_EOL, PHP_EOL, PHP_EOL);
    die();
} else {
    $currentMode = $currentOptions['mode'];

    if (empty($modes[$currentMode])) {
        echo sprintf("Mode `%s` did not configured. Check your config.php file.%s", $currentMode, PHP_EOL);
        die();
    }
}

$outputFile = isset($currentOptions['s']);
$bookId = (!empty($currentOptions['id'])) ? $currentOptions['id'] : null;
$startingPage = (!empty($currentOptions['start'])) ? $currentOptions['start'] : 0;
$downloadFolder = (!empty($currentOptions['path'])) ? $currentOptions['path'] : __DIR__;
$needToKeepTempFiles = isset($currentOptions['keep']);

echo 'Starting ...'.PHP_EOL;

if (false === file_exists($downloadFolder)) {
    try {
        mkdir($downloadFolder);
    } catch(\Exception $ex) {
        throw new \Exception(sprintf('Directory `%s` does not exists. Cannot create new, error message: `%s` ', $downloadFolder, $ex->getMessage()));
    }
}

if (false === is_writable($downloadFolder)) {
    throw new \Exception(sprintf('Directory `%s` is not writeable', $downloadFolder));
}

$storedImages = [];
for ($i = 0; $i <= $totalPages; $i++) {
    echo sprintf('Page %d ... ', $i);

    switch ($currentMode) {
        case 'dlib':
            $file = file_get_contents(sprintf($modes[$currentMode]['url_pattern'], $bookId, $i));
            break;
        case 'elib.shpl':
            if (empty($startingPage)) {
                throw new \Exception(sprintf('Starting page is not set for mode "%s"', $currentMode));
            }

            $currentPage = $startingPage + $i;
            $file = file_get_contents(sprintf($modes[$currentMode]['url_pattern'], $currentPage));
            break;
        case 'elib.tomsk':
            if (empty($startingPage)) {
                throw new \Exception(sprintf('Starting page is not set for mode "%s"', $currentMode));
            }

            $currentPage = $startingPage + $i;
            $file = file_get_contents(sprintf($modes[$currentMode]['url_pattern'], $bookId, $currentPage));
            break;
        default:
            throw new \Exception(sprintf('Unknown mode "%s"!', $currentMode));
            break;
    }

    $fullpath = sprintf('%s/%s', $downloadFolder, $i.'.jpg');
    file_put_contents($fullpath, $file);
    $storedImages[] = $fullpath;

    echo sprintf('OK%s', PHP_EOL);
}

if (!empty($storedImages) && $outputFile) {
    echo sprintf('%sStart compiling PDF ...%s', PHP_EOL, PHP_EOL);

    $outputPdf = new \Imagick($storedImages);
    $outputPdf->setImageFormat('pdf');
    $outputPdf->writeImages(sprintf('%s/%s', $downloadFolder, $outputFileName), true);

    if (false === $needToKeepTempFiles) {
        echo sprintf('%sCleaning tmp files... %s', PHP_EOL, PHP_EOL);

        foreach ($storedImages as $file) {
            unlink($file);
        }
    }
}

echo sprintf('%sDone!%s', PHP_EOL, PHP_EOL);
