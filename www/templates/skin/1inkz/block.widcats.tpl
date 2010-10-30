    <li id="category" class="block green">
        <div class="title"><a href="" class="link"><h1>Категории</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="category">
           {foreach from=$oConfig->GetValue('plugin.aceadminpanel.widcats') item=oCat}
               {assign var="sname" value="wid_category_$oCat"}
               <li>
                  <a href="?category={$oCat}" {if $_aRequest.category==$oCat}class="act"{/if}>{$aLang.$sname}</a>
               </li>
            {/foreach}
        </ul>
        <div class="block_bottom"></div>
        </div>
     </li>