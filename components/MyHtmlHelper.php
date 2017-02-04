<?php

namespace app\components;

use Yii,
    yii\helpers\Html;

class MyHtmlHelper {

    public static function a($text, $onclick, $params)
    {
        $params['onclick'] = $onclick;
        return Html::a($text, '#', $params);
    }

    public static function getSomeColor($i, $hash = false)
    {
        $colors = ['cc3399', '330066', '00ccff', '00ff66', 'cccc99', 'ff3366', 'cc0033', 'ff0033', 'ff9999', 'cc3366', 'ffccff', 'cc6699', '660033', 'ff99cc', 'ff66cc', 'ff99ff', 'ff6699', 'cc0066', 'ff3399', 'ff0099', 'ff33cc', 'ff00cc', 'ff66ff', 'ff33ff', 'ff00ff', '990066', 'cc66cc', 'cc33cc', 'cc99ff', 'cc66ff', 'cc33ff', '993399', 'cc00ff', '9900cc', '990099', 'cc99cc', '996699', '663366', '660099', '660066', '9900ff', '9933ff', '9966cc', '330033', '663399', '6633cc', '9966ff', '6600ff', '6633ff', 'ccccff', '9999ff', '9999cc', '6666ff', '666699', '333366', '333399', '330099', '3300cc', '3300ff', '3333cc', '0066ff', '0033ff', '3366ff', '3366cc', '000066', '000033', '000099', '0033cc', '0000cc', '336699', '0066cc', '99ccff', '6699ff', '6699cc', '006699', '3399cc', '0099cc', '66ccff', '3399ff', '003399', '33ccff', '99ffff', '66ffff', '33ffff', '00ffff', '00cccc', '669999', '99cccc', 'ccffff', '33cccc', '66cccc', '339999', '336666', '003333', '00ffcc', '33ffcc', '33cc99', '00cc99', '66ffcc', '99ffcc', '339966', '006633', '336633', '669966', '66cc66', '99ff99', '66ff66', '99cc99', '66ff99', '33ff99', '33cc66', '00cc66', '66cc99', '009966', '33ff66', 'ccffcc', 'ccff99', '99ff66', '99ff33', '00ff33', '00cc33', '33cc33', '66ff33', '00ff00', '66cc33', '006600', '003300', '33ff00', '66ff00', '99ff00', '66cc00', '00cc00', '33cc00', '339900', '669933', '99cc33', '336600', '669900', '99cc00', 'ccff66', 'ccff33', '999900', 'cccc00', 'cccc33', '333300', '666600', '999933', 'cccc66', '999966', 'ffffcc', 'ffff99', 'ffff66', 'ffff33', 'ffff00', 'ffcc66', 'ffcc33', 'cc9933', '996600', 'cc9900', 'ff9900', 'cc6600', 'cc6633', '663300', 'ff9966', 'ff6633', 'ff9933', 'ff6600', 'cc3300', '330000', '663333', '996666', 'cc9999', '993333', 'cc6666', 'ffcccc', 'cc3333', 'ff6666', '660000', '990000', 'cc0000', 'ff0000', 'ff3300', 'ffcc99', 'cccccc', '999999', '666666', '333333'];
        return ($hash ? '#' : '') . $colors[$i % count($colors)];
    }

    public static function customIcon($file, $title = '', $style = '', $attrs = [])
    {
        if (strpos($file, '.') === false) {
            $file = '/img/'.$file.'.png';
        }
        $attrs_str = '';
        if (is_array($attrs)) {
            foreach ($attrs as $key => $value) {
                $value = stripslashes(htmlspecialchars($value));
                $attrs_str .= " {$key}='{$value}' ";
            }
        } else {
            $attrs_str .= $attrs;
        }
        return "<img src='{$file}' alt='{$title}' title='{$title}' style='{$style}' {$attrs_str} />";
    }

    public static function icon($str, $style = 'vertical-align: baseline;', $attrs = [])
    {

        $lang = [
            'star' => 'Известность',
            'heart' => 'Доверие',
            'chart_pie' => 'Успешность',
            'coins' => 'золотых монет',
            'world' => 'Карта',
            'role' => 'Работа',
            'chart_bar' => 'Рейтинг',
            'ceo' => 'Политика',
            'check_box_list' => 'Выборы',
            'entity' => 'Государство',
            'profile' => 'Профиль',
            'SMI_32' => 'СМИ',
            'lcd_tv_image' => 'Тель-а-виденье',
            'newspaper' => 'Газеты',
            'radio_modern' => 'Радио',
            'twitter_bird' => 'Соц. сети',
            'money' => 'у.е.',
            'lg-icons/business' => 'Бизнес',
            'lg-icons/goverment' => 'Правительство',
            'lg-icons/map' => 'Карта',
            'lg-icons/globe' => 'Карта',
            'lg-icons/party' => 'Партия',
            'lg-icons/profile-female' => 'Профиль',
            'lg-icons/profile-male' => 'Профиль',
            'lg-icons/profile' => 'Профиль',
            'lg-icons/rating' => 'Рейтинг',
            'lg-icons/tv' => 'СМИ',
            'lg-icons/rss' => 'СМИ',
            'lg-icons/news' => 'СМИ',
            'lg-icons/work' => 'Работа',
        ];
        if (isset($lang[$str])) {
            return static::customIcon($str, $lang[$str], $style, $attrs);
        } else {
            return "";
        }
    }

    public static function zeroOne2Human($n)
    {
        if ($n === 0) {
            return 'нулевой уровень';
        } else if ($n < 0.3) {
            return 'крайне низкий уровень';
        } else if ($n < 0.5) {
            return 'низкий уровень';
        } else if ($n < 0.7) {
            return 'средний уровень';
        } else if ($n < 0.82) {
            return 'уровень выше среднего';
        } else if ($n < 0.95) {
            return 'высокий уровень';
        } else {
            return 'высочайший уровень';
        }
    }
    
    public static function oneTen2Human($n)
    {
        if ($n === 0) {
            return 'ужасное качество';
        } else if ($n < 3) {
            return 'крайне низкое качество';
        } else if ($n < 5) {
            return 'низкое качество';
        } else if ($n < 7) {
            return 'среднее качество';
        } else if ($n < 9) {
            return 'качество выше среднего';
        } else if ($n < 10) {
            return 'высокое качество';
        } else {
            return 'высочайшее';
        }
    }
    
    public static function zeroOne2Stars($n)
    {
        if ($n === 0) {
            return '<span style="color:#990000" title="нулевой уровень" >★</span>';
        } else if ($n < 0.3) {
            return '<span style="color:red" title="крайне низкий уровень" >★</span>';
        } else if ($n < 0.5) {
            return '<span style="color:#ED8931" title="низкий уровень" >★★</span>';
        } else if ($n < 0.7) {
            return '<span style="color:#1470DF" title="средний уровень" >★★★</span>';
        } else if ($n < 0.82) {
            return '<span style="color:#89891B" title="уровень выше среднего" >★★★★</span>';
        } else if ($n < 0.95) {
            return '<span style="color:#238619" title="высокий уровень" >★★★★★</span>';
        } else {
            return '<span style="color:gold" title="высочайший уровень" >★★★★★</span>';
        }
    }
    
    public static function oneTen2Stars($n)
    {
        if ($n === 0) {
            return '<span style="color:#990000" title="ужасное качество" >★</span>';
        } else if ($n < 3) {
            return '<span style="color:red" title="крайне низкое качество" >★</span>';
        } else if ($n < 5) {
            return '<span style="color:#ED8931" title="низкое качество" >★★</span>';
        } else if ($n < 7) {
            return '<span style="color:#1470DF" title="среднее качество" >★★★</span>';
        } else if ($n < 9) {
            return '<span style="color:#89891B" title="качество выше среднего" >★★★★</span>';
        } else if ($n < 10) {
            return '<span style="color:#238619" title="высокое качество" >★★★★★</span>';
        } else {
            return '<span style="color:gold" title="высочайшее качество" >★★★★★</span>';
        }
    }
    
    public static function zeroOne2Percents($n)
    {
        return number_format($n*100, 0, '.', ' ').'%';
    }

    // Функция которая возвращает правильное русское форматирование слов, стоящие после чисел
    // Например 0 комментариев, 1 комментарий, 2 комментария
    // На вход подается число и 3 варианта написание соответствующие 0,1 и 2
    // На выходе - строка в правильного вида.
    public static function formateNumberword($n, $s1 = false, $s2 = false, $s3 = false)
    {
        $pref = ($n < 0) ? '-' : '';
        $n = abs($n);
        $number = $pref . number_format($n, 0, '', ' ');
        
        if ($s1 === false) {
            return $number;
        }
        
        if ($s1 === 'h') {
            $s1 = 'человек';
            $s2 = 'человек';
            $s3 = 'человека';
        } elseif ($s1 === 'd') {
            $s1 = 'дней';
            $s2 = 'день';
            $s3 = 'дня';
        } elseif ($s1 === 's') {
            $s1 = 'акций';
            $s2 = 'акция';
            $s3 = 'акции';
        }
        if ($s2 === false) {
            $s2 = $s1;
        }
        if ($s3 === false) {
            $s3 = $s1;
        }

        if ($n === 0) {
            return '0 ' . $s1;
        } elseif ($n === 1 || ($n % 10 === 1 && $n % 100 != 11 && $n != 11)) {
            return $number . ' ' . $s2;
        } elseif ($n > 100 && $n % 100 >= 12 && $n % 100 <= 14) {
            return $number . ' ' . $s1;
        } elseif (($n % 10 >= 2 && $n % 10 <= 4 && $n > 20) || ($n >= 2 && $n <= 4)) {
            return $number . ' ' . $s3;
        } else {
            return $number . ' ' . $s1;
        }
    }

    // число в 16-ричный вид и строку с ведущим нулем
    public static function string16($n)
    {
        return ($n < 16 ? '0' : '') . dechex($n);
    }

    // цвет для партии, красные левые, центристы зеленые, синие правые.
    public static function getPartyColor($i, $hash = false)
    {

        $r = round(0xff - $i * 0xff / 0x65);
        $g = round(($i < 51) ? ($i * 0xff / 0x32) : (0xff - ($i - 51) * 0xff / 0x33));
        $b = round($i * 0xff / 0x65);
        return ($hash ? '#' : '') . static::string16($r) . static::string16($g) . static::string16($b);
    }

    // Транслитерация строк.
    public static function transliterate($st)
    {
        $cyr = array('а', 'б', 'в', 'г', 'д', 'e', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ь', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ь', 'Ю', 'Я', 'ў', 'ґ', 'ї', 'Ў', 'Ґ', 'Ї', 'ы', 'Ы', 'ё', 'Ё', 'э', 'Э');
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'y', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'Y', 'Yu', 'Ya', 'w', 'g', 'yi', 'W', 'G', 'Yi', 'y', 'Y', 'yo', 'Yo', 'e', 'E');
        return str_replace($cyr, $lat, $st);
    }

    public static function parseTwitterLinks($text)
    {
        $text = preg_replace_callback("/[@]+[A-Za-z0-9]+/", function($u) {
            if (isset($u[0])) {
                $u = $u[0];
                $username = str_replace("@", "", $u);
                return "<a href='#' onclick='load_page(\"twitter\",{\"nick\":\"" . $username . "\"})'>" . $u . "</a>";
            } else {
                return false;
            }
        }, $text);
        $text = preg_replace_callback("/[#]+[A-Za-zАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9_\-]+/u", function($u) {
            if (isset($u[0])) {
                $u = $u[0];
                $tag = str_replace("#", "", $u);
                return "<a href='#' onclick='load_page(\"twitter\",{\"tag\":\"" . $tag . "\"})'>" . $u . "</a>";
            } else {
                return false;
            }
        }, $text);

        return $text;
    }

    public static function aboutNumber($n)
    {
        switch (true) {
            case $n < 0:
                return '<&nbsp;0';
            case $n < 1000:
                return '<&nbsp;1&nbsp;000';
            case $n < 10000:
                return round($n / 1000) . '&nbsp;тыс.';
            case $n < 100000:
                return round($n / 10000) . '0&nbsp;тыс.';
            case $n < 1000000:
                return round($n / 100000) . '00&nbsp;тыс.';
            case $n < 10000000:
                return round($n / 1000000) . '&nbsp;млн.';
            case $n < 100000000:
                return round($n / 10000000) . '0&nbsp;млн.';
            case $n < 1000000000:
                return round($n / 100000000) . '00&nbsp;млн.';
            case $n < 10000000000:
                return round($n / 1000000000) . '&nbsp;млрд.';
            case $n < 100000000000:
                return round($n / 10000000000) . '0&nbsp;млрд.';
            case $n < 1000000000000:
                return round($n / 100000000000) . '00&nbsp;млрд.';
            default:
                return number_format(round($n / 1000000000000),0,'','&nbsp;') . '&nbsp;трлн.';
        }
    }

    public static function moneyFormat($money, $decimals = 0)
    {
        return '<span class="status-'.($money>0?'success':'error').'">'.number_format($money, $decimals, '.', '&nbsp;') . ' ' . static::icon('money', '').'</span>';
    }

    public static function timeFormatFuture($time)
    {
        $current = time();
        $time = intval($time);
        $d = $time - $current;
        
        if ($d < 60) {
            return "Осталось ".static::formateNumberword($d, "секунд", "секунда", "секунды");
        } elseif ($d < 3600) {
            return "Осталось ".static::formateNumberword(round($d/60), "минут", "минута", "минуты");
        } else {
            return "Осталось ".static::formateNumberword(round($d/3600), "часов", "час", "часа");
        }
    }
    
    public static function timeAutoFormat($time)
    {
        return "<span class='prettyDate' data-unixtime='{$time}'>".date('d-m-Y H:i', $time)."</span>";
    }
    
    public static function booleanToYesNo($value)
    {
        return $value ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
    }
    
}
