<?php

// params to set

$totalPages = 0;
$bookId = '';
$downloadFolder = '';
$currentMode = '';

// optional params
$startingPage = 0;

// supports
$modes = [
    'dlib' => 'http://dlib.rsl.ru/viewer/pdf?docId=%s&page=%d',
    'elib.shpl' => 'http://elib.shpl.ru/pages/%s/zooms/8',
    'elib.tomsk' => 'http://elib.tomsk.ru/elib/data/%s/%s.jpg',
];

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

for ($i = 0; $i <= $totalPages; $i++) {
    echo sprintf('Page %d ... ', $i);

    switch ($currentMode) {
        case 'dlib':
            $file = file_get_contents(sprintf($modes[$currentMode], $bookId, $i));
            break;
        case 'elib.shpl':
            if (empty($startingPage)) {
                throw new \Exception(sprintf('Starting page is not set for mode "%s"', $currentMode));
            }

            $currentPage = $startingPage + $i;
            $file = file_get_contents(sprintf($modes[$currentMode], $currentPage));
            break;
        case 'elib.tomsk':
            if (empty($startingPage)) {
                throw new \Exception(sprintf('Starting page is not set for mode "%s"', $currentMode));
            }

            $currentPage = $startingPage + $i;
            $file = file_get_contents(sprintf($modes[$currentMode], $bookId, $currentPage));
            break;
        default:
            throw new \Exception(sprintf('Unknown mode "%s"!', $currentMode));
            break;
    }

    file_put_contents(sprintf('%s/%s', $downloadFolder, $i.'.jpg'), $file);
    echo 'OK'.PHP_EOL;
}

echo PHP_EOL.'DONE! :)'.PHP_EOL;
?>
