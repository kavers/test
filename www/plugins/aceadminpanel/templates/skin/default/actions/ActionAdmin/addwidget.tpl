
{include file='header.tpl'}


        <li id="video_player" class="block2 green">
            <div class="title">
                            {if $aParams[0]=='add'}
                                    <h3>Добавление нового виджета:</h3>
                            {else}
                                    <h3>Редактирование виджета:</h3>
                            {/if}
            </div>
        <div class="block_content">

            <div class="text">
                <div class="long_text">
                    <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

                             <p>Категория виджета:
                                            <select name="wid_category" id="wid_category" class="select_line2">
                                            {foreach from=$oConfig->GetValue('plugin.aceadminpanel.widcats') item=oCat}
                                                    {assign var="sname" value="wid_category_$oCat"}
                                                    <option value="{$oCat}" {if $_aRequest.wid_category==$oCat}selected{/if}>{$aLang.$sname}</option>
                                            {/foreach}
                            </select></p>


                            <p>Название виджета:<br />
                            <input type="text" id="wid_title" name="wid_title" value="{$_aRequest.wid_title}" class="line" />
                            </p>

                            <p>Уникальный идентификатор автора виджета {if $aParams[0]=='add'}<span id="nha">(<a href="#" onclick="$('wid_name').value='{$hash}';$('nha').setStyle('display','none');return false;">новый hash&darr;</a>)</span>{/if}:<br />
                            
                            <input type="text" id="wid_name" name="wid_name" value="{if $aParams[0]=='edit'}{$_aRequest.wid_name}{/if}" class="line w30p"/>
                             <strong>Указывается на английском, без пробелов.</strong>
                           

                            </p>


                            <p>Код виджета:<br />
                            <textarea name="wid_description" id="wid_description" rows="10" style="width:100%" class="area">{$_aRequest.wid_description}</textarea><br />
                            <strong>html код виджета.</strong></p>

                            <p>Цена виджета в месяц:<br />
                            <input type="text" id="wid_price" name="wid_price" value="{$_aRequest.wid_price}" class="line" /><br />
                            <strong></strong></p>

                            <p>
                            {if $_aRequest.wid_avatar}
                                    <img src="{$_aRequest.wid_avatar}" />
                                    <label for="avatar_delete"><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; {$aLang.blog_create_avatar_delete}</label><br /><br />
                            {/if}
                            {$aLang.blog_create_avatar}:<br />
                            <input type="file" name="avatar" id="avatar"></p>

                            <p><input type="submit" name="submit_widget" value="{if $aParam[0]=='add'}Добавить виджет{else}Сохранить изменения{/if}">
                            <input type="hidden" name="blog_id" value="{$_aRequest.blog_id}"></p>

                    </form>
            </div>
            </div>
            <div class="block_bottom3"></div>
        </div>
        </li>

{include file='footer.tpl'}