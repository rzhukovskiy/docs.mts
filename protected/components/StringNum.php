<?php
class StringNum
{
    /**
     * Возвращает сумму прописью
     * @author runcore
     * @uses morph(...)
     */
    public static function num2str($num) {
        $nul='ноль';
        $ten=array(
            array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
            array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
        );
        $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
        $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
        $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
        $unit=array( // Units
            array('копейка' ,'копейки' ,'копеек',	 1),
            array('рубль'   ,'рубля'   ,'рублей'    ,0),
            array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
            array('миллион' ,'миллиона','миллионов' ,0),
            array('миллиард','милиарда','миллиардов',0),
        );
        //
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub)>0) {
            foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1; // unit key
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
                else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            } //foreach
        }
        else $out[] = $nul;
        $out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
        $out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    /**
     * Склоняем словоформу
     * @ author runcore
     */
    public static function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) return $f5;
        $n = $n % 10;
        if ($n>1 && $n<5) return $f2;
        if ($n==1) return $f1;
        return $f5;
    }

    public static function getMonthName($unixTimeStamp = false) {

        // Если не задано время в UNIX, то используем текущий
        if (!$unixTimeStamp) {
            $mN = date('m');
            // Если задано определяем месяц времени
        } else {
            $mN = date('m', (int)$unixTimeStamp);
        }

        $monthAr = array(
            1 => array('Январь', 'Января', 'Январе'),
            2 => array('Февраль', 'Февраля', 'Феврале'),
            3 => array('Март', 'Марта', 'Марте'),
            4 => array('Апрель', 'Апреля', 'Апреле'),
            5 => array('Май', 'Мая', 'Мае'),
            6 => array('Июнь', 'Июня', 'Июне'),
            7 => array('Июль', 'Июля', 'Июле'),
            8 => array('Август', 'Августа', 'Августе'),
            9 => array('Сентябрь', 'Сентября', 'Сентябре'),
            10=> array('Октябрь', 'Октября', 'Октябре'),
            11=> array('Ноябрь', 'Ноября', 'Ноябре'),
            12=> array('Декабрь', 'Декабря', 'Декабре')
        );

        return $monthAr[(int)$mN];
    }

    public static function getMonthNameByNum($num = false) {

        // Если не задано время в UNIX, то используем текущий
        if (!$num) {
            $num = date('m');
            // Если задано определяем месяц времени
        }

        $monthAr = array(
            1 => array('январь',    'января',   'январе'),
            2 => array('февраль',   'февраля',  'феврале'),
            3 => array('март',      'марта',    'марте'),
            4 => array('апрель',    'апреля',   'апреле'),
            5 => array('май',       'мая',      'мае'),
            6 => array('июнь',      'июня',     'июне'),
            7 => array('июль',      'июля',     'июле'),
            8 => array('август',    'августа',  'августе'),
            9 => array('сентябрь',  'сентября', 'сентябре'),
            10=> array('октябрь',   'октября',  'октябре'),
            11=> array('ноябрь',    'ноября',   'ноябре'),
            12=> array('декабрь',   'декабря',  'декабре')
        );

        return $monthAr[(int)$num];
    }

    /**
     * Функция возвращает окончание для множественного числа слова на основании числа и массива окончаний
     * @param  $number Integer Число на основе которого нужно сформировать окончание
     * @param  $endingsArray  Array Массив слов или окончаний для чисел (1, 4, 5),
     *         например array('яблоко', 'яблока', 'яблок')
     * @return String
     */
    public static function getNumEnding($number, $endingArray)
    {
        $number = $number % 100;
        if ($number>=11 && $number<=19) {
            $ending=$endingArray[2];
        }
        else {
            $i = $number % 10;
            switch ($i)
            {
                case (1): $ending = $endingArray[0]; break;
                case (2):
                case (3):
                case (4): $ending = $endingArray[1]; break;
                default: $ending=$endingArray[2];
            }
        }
        return $ending;
    }
}
