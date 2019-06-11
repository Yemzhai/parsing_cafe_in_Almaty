<?php
// парсинг сайта restourant.kz / cofe

function getCofesFromPage($page)
{
    $subject = file_get_contents('https://restoran.kz/cafe?page=' . $page);
    $pattern = '/<div class="place-story" data-site-id="[0-9]+">/u';
    $blocks = preg_split($pattern, $subject);
    unset($blocks[0]);
    $globalRests = [];

    foreach($blocks as $block){
        $pattern = '/<a class="place-story__title__link" href="(\/cafe\/[0-9a-z\-]{1,})">(.{1,}?)<\/a>/u';
        $result = [];
        preg_match_all($pattern, $block, $result);
    
        $rest = [
            'name' => $result[2][0],
            'link' => $result[1][0],
        ];

        $pattern = '/<dl class="row place-story__param"><dt class="col-xs-5 col-sm-3 place-story__param__title">(.{1,}?)<\/dt><dd class="col-xs-7 col-sm-9 place-story__param__content">(.{1,}?)<\/dd><\/dl>/u';
        $result = [];
        preg_match_all($pattern, $block, $result);

        $paramsMap = [
            'Кухня' => 'cuisine',
            'Средний счёт' => 'price',
            'Время работы' => 'worktime',
            'Адрес' => 'address',
        ];

        foreach($paramsMap as $key => $value){
            $index = array_search($key, $result[1]);
            if($index !== false){
                $rest[$value] = $result[2][$index];
            } else {
                $rest[$value] = '';
            }
        }

        $pattern = '/[0-9]+/u';
        $result = [];
        preg_match_all($pattern, $rest['price'], $result);
        $rest['price'] = [
            'min' => $result[0][0],
            'max' => isset($result[0][1]) ? $result[0][1] : $result[0][0]
        ];

        $rest['cuisine'] == isset($rest['cuisine']) ? $rest['cuisine'] : '' ;

        
 

        $globalRests[] = $rest;
    }
        return $globalRests;
}

function getMaxPage($page)
{
    $subject = file_get_contents('https://restoran.kz/cafe?page=' . $page);
    $pattern = '/\/cafe\?page=([0-9]+)/u';
    $result = [];
    preg_match_all($pattern, $subject, $result);

    $max = $result[1][0];
    foreach($result[1] as $value){
        if($value > $max){
            $max = $value;
        }
    }
    if($page == $max){
        return $max;
    } else {
        return getMaxPage($max);
    }
}
