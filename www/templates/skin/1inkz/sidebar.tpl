		<!-- Sidebar -->
  <ul id="column3" class="column right_colomn" id="column3">
<!-- Баннер почтового сервиса-->
     <li id="mail_banner" class="block">
		<script type="text/javascript" src="{cfg name='path.static.skin'}/js/ibanleft.js"></script>	
		<noscript><a href='http://adv.kaznetmedia.kz/www/delivery/ck.php?n=ad398848&amp;cb=<?php echo rand(); ?>' target='_blank'><img src='http://adv.kaznetmedia.kz/www/delivery/avw.php?zoneid=49&amp;charset=UTF-8&amp;cb=<?php echo rand(); ?>&amp;n=ad398848' border='0' alt='' /></a></noscript>	
        <!--<a href="#"><img src="{cfg name='path.static.skin'}/img/mail_banner.jpg" width="302" height="252" alt="Почта" title="Почта" /></a> -->
     </li>
<!-- Баннер почтового сервиса-->
    {if isset($aBlocks.right)}
        {foreach from=$aBlocks.right item=aBlock}
            {if $aBlock.type=='block'}
                {insert name="block" block=`$aBlock.name` params=`$aBlock.params`} 
            {/if}
            {if $aBlock.type=='template'}						 
                {include file=`$aBlock.name` params=`$aBlock.params`}
            {/if}	
        {/foreach}
    {/if}		
  </ul>
  
  <div>
  {*include file=header_nav.tpl*}
  </div>
  <p class="cl"></p> 
		<!-- /Sidebar -->

