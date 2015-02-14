<?php

namespace app\components;

use app\models\Resurse;

class MyHtmlHelper {
	public static function getSomeColor($i,$hash = false) 
	{	
		$colors = ['cc3399', '330066', '00ccff', '00ff66', 'cccc99', 'ff3366', 'cc0033', 'ff0033', 'ff9999', 'cc3366', 'ffccff', 'cc6699', '660033', 'ff99cc', 'ff66cc', 'ff99ff', 'ff6699', 'cc0066', 'ff3399', 'ff0099', 'ff33cc', 'ff00cc', 'ff66ff', 'ff33ff', 'ff00ff', '990066', 'cc66cc', 'cc33cc', 'cc99ff', 'cc66ff', 'cc33ff', '993399', 'cc00ff', '9900cc', '990099', 'cc99cc', '996699', '663366', '660099', '660066', '9900ff', '9933ff', '9966cc', '330033', '663399', '6633cc', '9966ff', '6600ff', '6633ff', 'ccccff', '9999ff', '9999cc', '6666ff', '666699', '333366', '333399', '330099', '3300cc', '3300ff', '3333cc', '0066ff', '0033ff', '3366ff', '3366cc', '000066', '000033', '000099', '0033cc', '0000cc', '336699', '0066cc', '99ccff', '6699ff', '6699cc', '006699', '3399cc', '0099cc', '66ccff', '3399ff', '003399', '33ccff', '99ffff', '66ffff', '33ffff', '00ffff', '00cccc', '669999', '99cccc', 'ccffff', '33cccc', '66cccc', '339999', '336666', '003333', '00ffcc', '33ffcc', '33cc99', '00cc99', '66ffcc', '99ffcc', '339966', '006633', '336633', '669966', '66cc66', '99ff99', '66ff66', '99cc99', '66ff99', '33ff99', '33cc66', '00cc66', '66cc99', '009966', '33ff66', 'ccffcc', 'ccff99', '99ff66', '99ff33', '00ff33', '00cc33', '33cc33', '66ff33', '00ff00', '66cc33', '006600', '003300', '33ff00', '66ff00', '99ff00', '66cc00', '00cc00', '33cc00', '339900', '669933', '99cc33', '336600', '669900', '99cc00', 'ccff66', 'ccff33', '999900', 'cccc00', 'cccc33', '333300', '666600', '999933', 'cccc66', '999966', 'ffffcc', 'ffff99', 'ffff66', 'ffff33', 'ffff00', 'ffcc66', 'ffcc33', 'cc9933', '996600', 'cc9900', 'ff9900', 'cc6600', 'cc6633', '663300', 'ff9966', 'ff6633', 'ff9933', 'ff6600', 'cc3300', '330000', '663333', '996666', 'cc9999', '993333', 'cc6666', 'ffcccc', 'cc3333', 'ff6666', '660000', '990000', 'cc0000', 'ff0000', 'ff3300', 'ffcc99', 'cccccc', '999999', '666666', '333333'];
		return ($hash ? '#' : '') . $colors[$i%sizeof($colors)];
	} 

	public static function icon($str,$style='',$attrs=[]) {
	    
		$lang = [
		    'star'=>'Известность',
		    'heart'=>'Доверие',
		    'chart_pie'=>'Успешность',
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
		    'oil' => 'Нефть',
			'natural_gas' => 'Природный газ',
			'coal' => 'Уголь',
			'nf_ores' => 'Руды цветных металлов',
			'f_ores' => 'Руды чёрных металлов',
			're_ores' => 'Руды редкоземельных металлов',
			'u_ores' => 'Урановые руды',
			'wood' => 'Древесина',
			'corn' => 'Зерно',
			'fruits' => 'Фрукты',
			'fish' => 'Рыба и морепродукты',
			'meat' => 'Мясо и молочная продукция',
			'wool' => 'Шерсть и кожа',
			'b_materials' => 'Стройматериалы (добываемые)'
		];
	    if (isset($lang[$str])) {

	    	$attrs_str = '';
	    	if (is_array($attrs)) {
	    		foreach ($attrs as $key => $value) {
	    			$attrs_str .= " {$key}='{$value}' ";
	    		}
	    	} else $attrs_str .= $attrs;	
	        return  "<img src='/img/{$str}.png' alt='{$lang[$str]}' title='{$lang[$str]}' style='{$style}' {$attrs_str} />";
	    } else return false;
	}

	public static function zeroOne2Human($n) {
	    if ($n === 0) {
	        return 'нулевой уровень';
	    } else if ($n<0.3) {
	        return 'крайне низкий уровень';
	    } else if ($n<0.5) {
	        return 'низкий уровень';
	    } else if ($n<0.7) {
	        return 'средний уровень';
	    } else if ($n<0.82) {
	        return 'уровень выше среднего';
	    } else if ($n<0.95) {
	        return 'высокий уровень';
	    } else {
	        return 'высочайший уровень';
	    }
	}

	// Функция которая возвращает правильное русское форматирование слов, стоящие после чисел
	// Например 0 комментариев, 1 комментарий, 2 комментария
	// На вход подается число и 3 варианта написание соответствующие 0,1 и 2
	// На выходе - строка в правильного вида.
	public static function formateNumberword($n,$s1,$s2 = false,$s3 = false) {
		if ($s1 === 'h') {
			$s1 = 'человек';
			$s2 = 'человек';
			$s3 = 'человека';
		}
		if ($s2 === false) $s2 = $s1;
		if ($s3 === false) $s3 = $s1;

	    $pref = ($n<0)?'-':'';
	    $n = abs($n);
	    if ($n === 0) {
	        return '0 '+$s1;
	    } else if ($n === 1 || ($n%10 === 1 && $n%100 != 11 && $n != 11)) {
	        return $pref.number_format($n,0,'',' ').' '.$s2;
	    } else if ($n >100 && $n%100 >=12 && $n%100 <=14) {
	        return $pref.number_format($n,0,'',' ').' '.$s1;
	    } else if (($n%10 >=2 && $n%10 <=4 && $n >20) || ($n >=2 && $n <=4)) {
	        return $pref.number_format($n,0,'',' ').' '.$s3;
	    } else {
	        return $pref.number_format($n,0,'',' ').' '.$s1;
	    }
	}

	// число в 16-ричный вид и строку с ведущим нулем
	public static function string16($n) {
	        return ($n<16?'0':'').dechex($n);
	}
	// цвет для партии, красные левые, центристы зеленые, синие правые.
	public static function getPartyColor($i,$hash = false) {
	    	$r = round(0xff-$i*0xff/0x65);
	        $g = round(($i<51)?($i*0xff/0x32):(0xff - ($i-51)*0xff/0x33));
	        $b = round($i*0xff/0x65);
	    return ($hash ? '#' : '') . static::string16($r). static::string16($g) . static::string16($b);
	}

	// Транслитерация строк.
	public static function transliterate($st) {
	  	$cyr  = array('а','б','в','г','д','e','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я','ў','ґ','ї','Ў','Ґ','Ї','ы','Ы','ё','Ё','э','Э');
        $lat = array('a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya','w','g','yi','W','G','Yi','y','Y','yo','Yo','e','E');
        return str_replace($cyr, $lat, $st);
	}

	public static function parseTwitterLinks($text) {
		return preg_replace_callback("/[@]+[A-Za-z0-9]+/", function($u) {
			if (isset($u[0])) {
				$u = $u[0];
            	$username = str_replace("@", "", $u);
            	return "<a href='#' onclick='load_page(\"twitter\",{\"nick\":\"".$username."\"})'>".$u."</a>";
            } else {
            	return false;
            }
        },$text);
	}
}