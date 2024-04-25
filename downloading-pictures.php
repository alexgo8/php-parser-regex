<?php

$destinationFolder = __DIR__ . '/' .'images/';
$imageLinksJson = __DIR__ . '/imageLinks.json';

if (!file_exists($destinationFolder)) {
  mkdir($destinationFolder, 0777, true);
}

$urlsArray = json_decode(file_get_contents($imageLinksJson), true);

foreach ($urlsArray as $url) {
  $directories = explode('/', rtrim($url, '/'));
  $lastTwoDir = array_slice($directories, -3, 2);
  $lastTwoFolder = implode('/', $lastTwoDir);  
  $saveFileFolder = $destinationFolder . $lastTwoFolder;
  
  if (!file_exists($saveFileFolder)) {
    mkdir($saveFileFolder, 0777, true);
  }
  $imageData = file_get_contents($url);
  if ($imageData === false) {
    echo 'Ошибка чтения файла по адресу' . $url . PHP_EOL;
    continue;
  }
  $save = $saveFileFolder . '/' . basename($url); 
  $result = file_put_contents($save, $imageData);
  if ($result === false) {
    echo 'Ошибка записи файла по адресу' . $saveFileFolder . PHP_EOL;
    continue;
  }
}

echo 'Изображения успешно сохранены в папку ' . $destinationFolder . PHP_EOL;