 <li id="category" class="block green">
    <div class="title"><h1>Найдено</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
    <div class="block_content">
    <ul class="category">
       {foreach from=$aRes.aCounts item=iCount key=sType name="sTypes"}
       <li>
        <a href="{router page='search'}{$sType}/?q={$aReq.q|escape:'html'}" {if $aReq.sType == $sType}class="act"{/if}>
            {if $sType=="topics"}
                {$aLang.search_results_count_topics}
            {elseif $sType=="comments"}
                {$aLang.search_results_count_comments}
            {/if}
        </a>
          <strong>{$iCount}</strong>
       </li>
       {/foreach}
    </ul>
    <div class="block_bottom"></div>
    </div>
 </li>
