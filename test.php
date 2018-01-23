<?php

$fileName = "file.txt";
$key = "A";

function binarySearch($fileName, $key)
{
    $file = new SplFileObject($fileName);

    /* проверяем первую строку */
    $str = $file->current();
    $str = strstr($str, "\t", true);
    if ($key == $str) {
        return $key;
    } elseif ($key < $str) {
        return 'undef';
    }

    $fileSize = fileSize($fileName);
    $log = ceil(log($fileSize, 2)); // максимальное число проходов цикла
    $fraction = 0.5; // за каждый оборот цикла дробь будет уменьшаться вдвое
    $point = round($fraction * $fileSize); // позиция указателя

    for ($i = 1; $i <= $log; $i++) {/* бинарная проверка записей */

        $file->fseek($point);


        /* идём к следующей строке. next() в данном случае не подходит, приходится использовать fgets() или current() */
        $file->current();

        /* если упёрлись в край файла (такое может быть, если предпоследняя запись сильно короче последней), то смещаемся назад */
        if (!$file->valid()) {
            $point = $point - round($fraction * $fileSize);
            $file->fseek($point);
            $file->current();
        }

        /* проверка этой записи */
        $str = $file->fgets();
        $str = strstr($str, "\t", true);

        $fraction = $fraction * 0.5;

        if ($key > $str) {
            $point = $point + round($fraction * $fileSize);
        } elseif ($key < $str) {
            $point = $point - round($fraction * $fileSize);
        } elseif ($key == $str) {
            return $key;
        }

    }

    return 'undef';
}


echo binarySearch($fileName, $key);

?>
