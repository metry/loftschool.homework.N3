<?php

function task1($path)
{
    if (!file_exists($path)) {
        return null;
    }
    $file = file_get_contents($path);
    $xml = new SimpleXMLElement($file);

    $msg = '<h1>Purchase order</h1>';
    $msg .= '<h3>Num. ' . $xml->attributes()->PurchaseOrderNumber->__toString() . '</h3>';
    $msg .= '<h3>From: ' . $xml->attributes()->OrderDate->__toString() . '</h3>';
    $msg .= '<h3>Addresses:</h3>';
    foreach ($xml->Address as $address) {
        $msg .= 'Type: ' . $address->attributes()->Type->__toString() . '<br>';
        $msg .= 'To: ';
        $msg .= $address->Zip->__toString() . ', ';
        $msg .= $address->Country->__toString() . ', ';
        $msg .= $address->State->__toString() . ', ';
        $msg .= $address->City->__toString() . ', ';
        $msg .= $address->Street->__toString() . '<br>';
        $msg .= 'Person: ' . $address->Name->__toString() . '<br>';
        $msg .= '<br>';
    }
    $msg .= '<h3>Items list:</h3>';
    foreach ($xml->Items->Item as $item) {
        $msg .= 'Article: ' . $item->attributes()->PartNumber->__toString() . '<br>';
        $msg .= $item->ProductName->__toString() . '<br>';
        $msg .= 'Quantity: ' . $item->Quantity->__toString() . '<br>';
        $msg .= 'US Price: ' . $item->USPrice->__toString() . '<br>';
        if ($item->Comment) {
            $msg .= 'Comment: ' . $item->Comment->__toString() . '<br>';
        }
        if ($item->ShipDate) {
            $msg .= 'Ship date: ' . $item->ShipDate->__toString() . '<br>';
        }
        $msg .= '<br>';
    }
    $msg .= '<h3>Delivery notes:</h3>';
    $msg .= $xml->DeliveryNotes->__toString();
    echo $msg;
}

function task2($path)
{
    if (!file_exists($path)) {
        return null;
    }
    $array = json_decode(file_get_contents($path), true);

    $func = function ($value) use (&$func) {
        if (!is_array($value)) {
            if (rand(0, 1)) {
                return 'changed value';
            }
            return $value;
        }
        return array_map($func, $value);
    };
    $newArray = array_map($func, $array);
    file_put_contents('data/output2.json', json_encode($newArray));
    diffMultidimentional($array, $newArray);
}

function diffMultidimentional($array1, $array2, $arrayName = '', $level = 0)
{
    foreach ($array1 as $itemKey => $itemVal) {
        if (is_array($itemVal)) {
            diffMultidimentional($array1[$itemKey], $array2[$itemKey], $itemKey, ++$level);
        } else {
            if ($itemVal !== $array2[$itemKey]) {
                echo 'На ' . $level . '-ом уровне вложенности ';
                echo 'имеется отличие по ключу ' . $itemKey . ' с начальным значением ' . $itemVal;
                if ($arrayName) {
                    echo ' (массив: ' . $arrayName . ')';
                }
                echo '<br>';
            }
        }
    }
}

function task3()
{
    $arrayLength = 50;
    $path = 'data/output.csv';
    $delimiter = ';';
    $array = [];
    for ($i = 1; $i <= $arrayLength; $i++) {
        $array[] = rand(1, 100);
    }
    //в условии не увидел, что массив должен разбиваться на строки, поэтому формирую csv простым способом.
    file_put_contents($path, implode($delimiter, $array));
    echo 'Файл успешно записан<br>';
    //открываю файл только потому, что так написано в условии (причем читаю построчно, т.е общий случай),
    // можно было использовать готовый массив $array
    $csvFile = fopen($path, "r");
    //уточнить про такую проверку
    if (!$csvFile) {
        echo 'Файл не существует';
        return null;
    }
    $res = [];
    while (($csvData = fgetcsv($csvFile, 100, $delimiter)) !== false) {
        $res[] = $csvData;
    }
    fclose($csvFile);
    //обработка файла
    $sum = 0;
    foreach ($res as $row) {
        foreach ($row as $col) {
            if ($col%2 == 0) {
                $sum += $col;
            }
        }
    }
    echo 'Сумма четных чисел: ' . $sum;
}

function task4($url)
{
    $json = @file_get_contents($url);
    if (!$json) {
        echo 'HTTPS request failed';
        return null;
    }
    $data = json_decode($json, true);
    foreach ($data['query']['pages'] as $page) {
        echo $url . '<br>';
        echo 'title: ' . $page['title'] . '<br>';
        echo 'Page Id: ' . $page['pageid'] . '<br>';
    }
}
