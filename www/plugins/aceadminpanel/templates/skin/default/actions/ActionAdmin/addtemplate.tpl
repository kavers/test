
{include file='header.tpl'}


        <li id="video_player" class="block2 green">
            <div class="title">
                            {if $aParam[0]=='add'}
                                    <h3>Добавление нового оформления</h3>
                            {else}
                                    <h3>Редактирование оформления:</h3>
                            {/if}
            </div>
        <div class="block_content">

            <div class="text">
                <div class="long_text">
                    <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

                             <p>Категория шаблона:
                                            <select name="tpl_category" id="tpl_category" class="select_line2">
                                            {foreach from=$oConfig->GetValue('plugin.aceadminpanel.tplcats') item=oCat}
                                                    {assign var="sname" value="tpl_category_$oCat"}
                                                    <option value="{$oCat}" {if $_aRequest.tpl_category==$oCat}selected{/if}>{$aLang.$sname}</option>
                                            {/foreach}
                            </select></p>


                            <p>Название шаблона:<br />
                            <input type="text" id="tpl_title" name="tpl_title" value="{$_aRequest.tpl_title}" class="line" />
                            </p>

                            <p>"Внутреннее" название шаблона:<br />
                            <input type="text" id="tpl_name" name="tpl_name" value="{$_aRequest.tpl_name}" class="line"/><br />
                            <strong>Указывается на английском, без пробелов. Совпадает с названием css файла - т.е. если нужно добавить файл apple.css внутреннее название - apple</strong></p>


                            <p>Описание шаблона:<br />
                            <textarea name="tpl_description" id="tpl_description" rows="10" style="width:100%" class="area">{$_aRequest.tpl_description}</textarea><br />
                            <strong>Краткое описание шаблона.</strong></p>

                            <p>Цена шаблона в месяц:<br />
                            <input type="text" id="tpl_price" name="tpl_price" value="{$_aRequest.tpl_price}" class="line" /><br />
                            <strong></strong></p>

                            <p>
                            {if $_aRequest.tpl_avatar}
                                    <img src="{$_aRequest.tpl_avatar}" />
                                    <label for="avatar_delete"><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; {$aLang.blog_create_avatar_delete}</label><br /><br />
                            {/if}
                            {$aLang.blog_create_avatar}:<br />
                            <input type="file" name="avatar" id="avatar"></p>

                            <p><input type="submit" name="submit_template" value="{if $aParam[0]=='add'}Добавить оформление{else}Сохранить изменения{/if}">
                            <input type="hidden" name="blog_id" value="{$_aRequest.blog_id}"></p>

                    </form>
            </div>
            </div>
            <div class="block_bottom3"></div>
        </div>
        </li>

{include file='footer.tpl'}