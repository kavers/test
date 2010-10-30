
{include file='header.tpl'}


        <li id="video_player" class="block2 green">
            <div class="title">
                            {if $aParam[0]=='add'}
                                    <h3>Добавление нового украшения</h3>
                            {else}
                                    <h3>Редактирование украшения:</h3>
                            {/if}
            </div>
        <div class="block_content">

            <div class="text">
                <div class="long_text">
                    <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

                             <p>Категория украшения:
                                            <select name="dec_category" id="dec_category" class="select_line2">
                                            {foreach from=$oConfig->GetValue('plugin.aceadminpanel.deccats') item=oCat}
                                                    {assign var="sname" value="dec_category_$oCat"}
                                                    <option value="{$oCat}" {if $_aRequest.dec_category==$oCat}selected{/if}>{$aLang.$sname}</option>
                                            {/foreach}
                            </select></p>


                            <p>Название украшения:<br />
                            <input type="text" id="dec_title" name="dec_title" value="{$_aRequest.dec_title}" class="line" />
                            </p>

                            <p>Позиция украшения:<br />
                             <select name="dec_position" id="dec_position" class="select_line2">
                                            {foreach from=$oConfig->GetValue('plugin.aceadminpanel.decpositions') item=oPosition}
                                                    {assign var="sname" value="dec_position_$oPosition"}
                                                    <option value="{$oPosition}" {if $_aRequest.dec_position==$oCat}selected{/if}>{$aLang.$sname}</option>
                                            {/foreach}
                            </select>
                            </p>


                            <p>HTML код украшения:<br />
                            <textarea name="dec_description" id="dec_description" rows="10" style="width:100%" class="area">{$_aRequest.dec_description}</textarea><br />
                            </p>

                            <p>Цена украшения в месяц:<br />
                            <input type="text" id="dec_price" name="dec_price" value="{$_aRequest.dec_price}" class="line" /><br />
                            <strong></strong></p>

                            <p>
                            {if $_aRequest.dec_avatar}
                                    <img src="{$_aRequest.dec_avatar}" />
                                    <label for="avatar_delete"><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; {$aLang.blog_create_avatar_delete}</label><br /><br />
                            {/if}
                            {$aLang.blog_create_avatar}:<br />
                            <input type="file" name="avatar" id="avatar"></p>

                            <p><input type="submit" name="submit_decor" value="{if $aParam[0]=='add'}Добавить украшение{else}Сохранить изменения{/if}">
                            <input type="hidden" name="blog_id" value="{$_aRequest.blog_id}"></p>

                    </form>
            </div>
            </div>
            <div class="block_bottom3"></div>
        </div>
        </li>

{include file='footer.tpl'}