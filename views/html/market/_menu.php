<ul class="nav nav-tabs">
  <li <?=$active === 0?'class="active"':''?>><a href="#!market" <?=$active !== 0?'onclick="load_page(\'market\')"':''?> >Общая информация</a></li>
  <li <?=$active === 1?'class="active"':''?> class="disabled" ><a href="#!market-forex" <?=$active !== 1?'onclick="/*load_page(\'market-forex\')*/"':''?> >Рынок валют</a></li>
  <li <?=$active === 2?'class="active"':''?> ><a href="#!market-resources" <?=$active !== 2?'onclick="load_page(\'market-resources\')"':''?> >Рынок ресурсов</a></li>
  <li <?=$active === 3?'class="active"':''?> class="disabled" ><a href="#!market-stocks" <?=$active !== 3?'onclick="/*load_page(\'market-stocks\')*/"':''?> >Рынок акций</a></li>
  <li <?=$active === 4?'class="active"':''?>><a href="#!market-factories" <?=$active !== 4?'onclick="load_page(\'market-factories\')"':''?> >Рынок недвижимости</a></li>
</ul>