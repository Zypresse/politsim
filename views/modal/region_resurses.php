<?php
use app\components\MyHtmlHelper;
?>
<h1><?=htmlspecialchars($region->name)?></h1>
<? if ($region->state_id) { ?><p><? if ($region->isCapital()) { ?>Столица государства<? } else { ?>Принадлежит государству<? } ?> &laquo;<a href='#' onclick="$('.modal-backdrop').hide(); load_page('state_info',{'id':<?=$region->state_id?>});" ><?=htmlspecialchars($region->state->name)?></a>&raquo;</p><? } ?>
<h3>Эффективность добычи ресурсов в этом регионе</h3>
<ul>
	<? if ($region->oil) { ?><li><?=MyHtmlHelper::icon('oil')?> Нефть — <?=MyHtmlHelper::zeroOne2Human($region->oil)?></li><? } ?>
	<? if ($region->natural_gas) { ?><li><?=MyHtmlHelper::icon('natural_gas')?> Натуральный газ — <?=MyHtmlHelper::zeroOne2Human($region->natural_gas)?></li><? } ?>
	<? if ($region->coal) { ?><li><?=MyHtmlHelper::icon('coal')?> Уголь — <?=MyHtmlHelper::zeroOne2Human($region->coal)?></li><? } ?>
	<? if ($region->nf_ores) { ?><li><?=MyHtmlHelper::icon('nf_ores')?> Руды цветных металлов — <?=MyHtmlHelper::zeroOne2Human($region->nf_ores)?></li><? } ?>
	<? if ($region->f_ores) { ?><li><?=MyHtmlHelper::icon('f_ores')?> Железная руда — <?=MyHtmlHelper::zeroOne2Human($region->f_ores)?></li><? } ?>
	<? if ($region->re_ores) { ?><li><?=MyHtmlHelper::icon('re_ores')?> Руды редкоземельных металлов — <?=MyHtmlHelper::zeroOne2Human($region->re_ores)?></li><? } ?>
	<? if ($region->u_ores) { ?><li><?=MyHtmlHelper::icon('u_ores')?> Урановая руда — <?=MyHtmlHelper::zeroOne2Human($region->u_ores)?></li><? } ?>
	<? if ($region->wood) { ?><li><?=MyHtmlHelper::icon('wood')?> Древесина — <?=MyHtmlHelper::zeroOne2Human($region->wood)?></li><? } ?>
	<? if ($region->corn) { ?><li><?=MyHtmlHelper::icon('corn')?> Зерно — <?=MyHtmlHelper::zeroOne2Human($region->corn)?></li><? } ?>
	<? if ($region->fruits) { ?><li><?=MyHtmlHelper::icon('fruits')?> Фрукты — <?=MyHtmlHelper::zeroOne2Human($region->fruits)?></li><? } ?>
	<? if ($region->fish) { ?><li><?=MyHtmlHelper::icon('fish')?> Рыба и морепродукты — <?=MyHtmlHelper::zeroOne2Human($region->fish)?></li><? } ?>
	<? if ($region->meat) { ?><li><?=MyHtmlHelper::icon('meat')?> Мясо и молочная продукция — <?=MyHtmlHelper::zeroOne2Human($region->meat)?></li><? } ?>
	<? if ($region->wool) { ?><li><?=MyHtmlHelper::icon('wool')?> Шерсть и кожа — <?=MyHtmlHelper::zeroOne2Human($region->wool)?></li><? } ?>
	<? if ($region->b_materials) { ?><li><?=MyHtmlHelper::icon('b_materials')?> Стройматериалы (добываемые) — <?=MyHtmlHelper::zeroOne2Human($region->b_materials)?></li><? } ?>
</ul>