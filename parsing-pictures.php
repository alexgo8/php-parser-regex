<?php

$startUrl = 'https://xn----7sbbjla0beok1a5pf.xn--p1ai/'; // введите стартовую страницу сайта
$imageUrls = array();
$parsedUrls = array();
function parsing($url)
{
  global $imageUrls;
  global $parsedUrls;
  global $startUrl;

  $headers = get_headers($url);

  if (strpos($headers[3], 'image')) {
    $imageUrls[] = $url;
    return;
  }

  if ($headers and strpos($headers[0], '200') !== false) {
    if (strpos($headers[3], 'html')) {
      $html = file_get_contents($url);
      preg_match_all('/<a\s+(?:[^>]*?\s+)?href="([^"]*)"/i', $html, $matches);

      if (!empty($matches[1])) {
        foreach ($matches[1] as $link) {

          if (strpos($link, 'https') !== 0) {
            $link = rtrim($startUrl, '/') . '/' . ltrim($link, '/');
          }
          if (strpos($link, $startUrl) !== false) {
            $headers = get_headers($link);
            if (strpos($headers[3], 'image')) {
              $imageUrls[] = $link;
              continue;
            }
            if (strpos($headers[3], 'text/html') and in_array($link, $parsedUrls) === false) {
              echo $link . PHP_EOL;
              $parsedUrls[] = $link;
              parsing($link);
            }
          }
        }
        return;
      } else {
        return;
      }
    }
  }
}


parsing($startUrl);
echo  PHP_EOL . 'Изображения' . PHP_EOL;
foreach ($imageUrls as $imageUrl) {
  echo $imageUrl . PHP_EOL;
}

$json = __DIR__ . '/imageLinks.json';

if (file_exists($json)) {
  $jsonData = json_encode($imageUrls, JSON_PRETTY_PRINT);
  file_put_contents($json, $jsonData);
  echo 'Ссылки на изображения успешно сохранены в JSON файл';
} else {
  echo 'Файл JSON не найден';
}