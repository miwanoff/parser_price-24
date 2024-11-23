<?php

$homepage = file_get_contents('http://price.ua/');
$names = array();
$prices = array();
$pattern_div = "#<div[^>]*?\"title ga_container\"[^>]*?>(.*?)</div[^>]*>#"; //<div> с классом title ga_container
preg_match_all($pattern_div, $homepage, $matches_div);

$pattern_a = "#<a[^>]*?>(.*?)</a[^>]*>#"; //<a> с классом ga_popular_mdl_title

foreach ($matches_div[1] as $item) { //из найденных <div> выбираем нужные <a> 
    preg_match($pattern_a, $item, $matches_a);

    if (count($matches_a) > 0)
        $names[] = $matches_a[1]; //содержимое "кармана" - название телефона
}

print_r($names);

$pattern_value = "#<span[^>]*?value[^>]*?>(.*?)</span[^>]*>#";

preg_match_all($pattern_value, $homepage, $prices);//цена телефона
if (count($prices[1]) > 0) {
    array_shift($prices[1]); //удаляем первый элемент
    array_splice($prices[1], count($names)); //делаем равным количество элементов    
    print_r($prices[1]); 
}

$pattern_a_buy = "#<a[^>]*?>(.*?)</a[^>]*>#";

$csv = array();
$csv[] = $names;
$csv[] = $prices[1];
print_r($csv);

$fp = fopen('file.csv', 'w'); //записываем в файл csv

foreach ($csv as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
