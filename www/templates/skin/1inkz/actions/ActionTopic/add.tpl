{include file='header.tpl' menu='topic_action' showWhiteBack=true}
<script>
{literal}
jQuery.noConflict();
jQuery(document).ready(function($){

function show_reply(el){
    var text = $('#add_c textarea')[0] && $('#add_c textarea')[0].value || '';
    $('#add_c').remove();
    $(el).hide().parent().parent().after('<div id="add_c" class="add_comment" style="width:'
        + ($(el).hide().parent().parent().width()) + 'px;margin-left:'
        + (parseInt($(el).hide().parent().parent().css('margin-left'))||12) + 'px;">'
        + '<form action="" method="post"><textarea>' + text + '</textarea>'
        + '<input type="submit" class="submit" value="" /></form></div>');
}
$(document).ready(function(){
    $('dl.comment_item').hover(
        function(e){
            if($(this).next().attr('id') != 'add_c')
                $($(this).children()[0]).prepend('<div class="add"><a href="#"><img src="{/literal}{cfg name='path.static.skin'}{literal}/img/comment_ico3.png" width="24" height="24" alt="" /></a><a href="#"><img src="{/literal}{cfg name='path.static.skin'}{literal}/img/comment_ico2.png" width="24" height="24" alt="" /></a><a href="#" onclick="show_reply(this.offsetParent);return false;"><img src="{/literal}{cfg name='path.static.skin'}{literal}/img/comment_ico1.png" width="24" height="24" alt="" /></a></div>');
        },
        function(){$(this).find('.add').remove();}
    );
});
});
{/literal}
</script>
<!-- Редактирование записи -->
     <li id="video_player" class="block2 green">
        <div class="title"><a href="#" class="link"><h1>
                {if $sEvent=='add'}
					{$aLang.topic_topic_create}
				{else}
					{$aLang.topic_topic_edit}
				{/if}</h1></a></div>
        <div class="block_content">
          <div id="text">
				<form action="" method="POST" enctype="multipart/form-data">
					{hook run='form_add_topic_topic_begin'}
					<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
             <div class="right_text settings" style="width:100%">
			
             <div class="topic" style="display: none;">
				<div class="content" id="text_preview" style="font-size:11px;font-weight:normal;color:#767676;padding:0 15px"></div>
			</div>
            
               <div id="add_book_format">
               
					<p>{$aLang.topic_create_blog}
					<select name="blog_id" id="blog_id" onChange="ajaxBlogInfo(this.value);">
     					<option value="0">{$aLang.topic_create_blog_personal}</option>
     					{foreach from=$aBlogsAllow item=oBlog}
     						<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
     					{/foreach}     					
     				</select></p>
					
     				<script language="JavaScript" type="text/javascript">
     					ajaxBlogInfo($('blog_id').value);
     				</script>
					
					<p>{$aLang.topic_create_title}:<br />
					<input class="line" type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="w100p" /><br />
       				<strong>{$aLang.topic_create_title_notice}</strong>
					</p>

					<p>{$aLang.topic_create_text}:<br />
                        {if !$oConfig->GetValue('view.tinymce')}<strong>{$aLang.topic_create_text_notice}</strong>{/if}
                    <div style="padding:0 15px">
					{if !$oConfig->GetValue('view.tinymce')}
            			<div class="panel_form">
							<select onchange="lsPanel.putTagAround('topic_text',this.value); this.selectedIndex=0; return false;" style="width: 91px;">
            					<option value="">{$aLang.panel_title}</option>
            					<option value="h4">{$aLang.panel_title_h4}</option>
            					<option value="h5">{$aLang.panel_title_h5}</option>
            					<option value="h6">{$aLang.panel_title_h6}</option>
            				</select>            			
            				<select onchange="lsPanel.putList('topic_text',this); return false;">
            					<option value="">{$aLang.panel_list}</option>
            					<option value="ul">{$aLang.panel_list_ul}</option>
            					<option value="ol">{$aLang.panel_list_ol}</option>
            				</select>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>	 			
	 						&nbsp;
	 						<a href="#" onclick="lsPanel.putTagUrl('topic_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
	 						<a href="#" onclick="lsPanel.putQuote('topic_text'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/quote.gif" width="20" height="20" title="{$aLang.panel_quote}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','video'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/video.gif" width="20" height="20" title="{$aLang.panel_video}"></a>
	 				
	 						<a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/img.gif" width="20" height="20" title="{$aLang.panel_image}"></a> 			
	 						<a href="#" onclick="lsPanel.putText('topic_text','<cut>'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/cut.gif" width="20" height="20" title="{$aLang.panel_cut}"></a>	
	 					</div>
	 				{/if}
					<textarea class="area" name="topic_text" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea></p>
					</div>
					<p>{$aLang.topic_create_tags}:
					<input class="line" type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="w100p" />
       				<strong>{$aLang.topic_create_tags_notice}</strong></p>
												
					<p><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if}/> 
					&mdash; {$aLang.topic_create_forbid_comment}<br>
					<strong>{$aLang.topic_create_forbid_comment_notice}</strong></p>

					{if $oUserCurrent->isAdministrator()}
						<p><input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if}/> 
						&mdash; {$aLang.topic_create_publish_index}<br />
						<strong>{$aLang.topic_create_publish_index_notice}</strong></p>
					{/if}
										
					{hook run='html_pluginTopicadditionalfields_form'}
					{hook run='html_pluginAccesstotopic'}
					{hook run='html_pluginTopicfix_form'}					
					{hook run='form_add_topic_topic_end'}	
				
					<p class="buttons">
					<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" class="right" />
					<input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="$('text_preview').getParent('div').setStyle('display','block'); ajaxTextPreview('topic_text',false); return false;" />&nbsp;
					{*
                    <input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
                    *}
					</p>
                    </div>
                    </div>
				</form>
                
                
                
                
         {*    <p  class="cn"><a href="#"><img src="{cfg name='path.static.skin'}/img/add_button.gif" width="212" height="32" alt="Добавить" title="Добавить"/></a></p>
         *} </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
<!-- Редактирование записи -->


{literal}
<script language="JavaScript" type="text/javascript">
document.addEvent('domready', function() {	
	new Autocompleter.Request.HTML($('topic_tags'), DIR_WEB_ROOT+'/include/ajax/tagAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY, {
		'indicatorClass': 'autocompleter-loading', // class added to the input during request
		'minLength': 2, // We need at least 1 character
		'selectMode': 'pick', // Instant completion
		'multiple': true // Tag support, by default comma separated
	}); 
});
</script>
{/literal}


{if $oConfig->GetValue('view.tinymce')}
<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce_3.2.7/tiny_mce.js"></script>

<script type="text/javascript">
{literal}
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_buttons1 : "lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,pagebreak,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : 0,
	theme_advanced_resizing_use_cookie : 0,
	theme_advanced_path : false,
	object_resizing : true,
	force_br_newlines : true,
    forced_root_block : '', // Needed for 3.x
    force_p_newlines : false,    
    plugins : "lseditor,safari,inlinepopups,media,pagebreak",
    convert_urls : false,
    extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
    pagebreak_separator :"<cut>",
    language : TINYMCE_LANG
});
{/literal}
</script>

{else}
	{include file='window_load_img.tpl' sToLoad='topic_text'}
{/if}


			
{*
			<div class="topic" style="display: none;">
				<div class="content" id="text_preview"></div>
			</div>

			<div class="profile-user">
				{if $sEvent=='add'}
					<h1>{$aLang.topic_topic_create}</h1>
				{else}
					<h1>{$aLang.topic_topic_edit}</h1>
				{/if}
				<form action="" method="POST" enctype="multipart/form-data">
					{hook run='form_add_topic_topic_begin'}
					<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
					
					<p><label for="blog_id">{$aLang.topic_create_blog}</label>
					<select name="blog_id" id="blog_id" onChange="ajaxBlogInfo(this.value);">
     					<option value="0">{$aLang.topic_create_blog_personal}</option>
     					{foreach from=$aBlogsAllow item=oBlog}
     						<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
     					{/foreach}     					
     				</select></p>
					
     				<script language="JavaScript" type="text/javascript">
     					ajaxBlogInfo($('blog_id').value);
     				</script>
					
					<p><label for="topic_title">{$aLang.topic_create_title}:</label><br />
					<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="w100p" /><br />
       				<span class="form_note">{$aLang.topic_create_title_notice}</span>
					</p>

					<p>{if !$oConfig->GetValue('view.tinymce')}<div class="note">{$aLang.topic_create_text_notice}</div>{/if}<label for="topic_text">{$aLang.topic_create_text}:</label>
					{if !$oConfig->GetValue('view.tinymce')}
            			<div class="panel_form">
							<select onchange="lsPanel.putTagAround('topic_text',this.value); this.selectedIndex=0; return false;" style="width: 91px;">
            					<option value="">{$aLang.panel_title}</option>
            					<option value="h4">{$aLang.panel_title_h4}</option>
            					<option value="h5">{$aLang.panel_title_h5}</option>
            					<option value="h6">{$aLang.panel_title_h6}</option>
            				</select>            			
            				<select onchange="lsPanel.putList('topic_text',this); return false;">
            					<option value="">{$aLang.panel_list}</option>
            					<option value="ul">{$aLang.panel_list_ul}</option>
            					<option value="ol">{$aLang.panel_list_ol}</option>
            				</select>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>	 			
	 						&nbsp;
	 						<a href="#" onclick="lsPanel.putTagUrl('topic_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
	 						<a href="#" onclick="lsPanel.putQuote('topic_text'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/quote.gif" width="20" height="20" title="{$aLang.panel_quote}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','video'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/video.gif" width="20" height="20" title="{$aLang.panel_video}"></a>
	 				
	 						<a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/img.gif" width="20" height="20" title="{$aLang.panel_image}"></a> 			
	 						<a href="#" onclick="lsPanel.putText('topic_text','<cut>'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/cut.gif" width="20" height="20" title="{$aLang.panel_cut}"></a>	
	 					</div>
	 				{/if}
					<textarea name="topic_text" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea></p>
					
					<p><label for="topic_tags">{$aLang.topic_create_tags}:</label><br />
					<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="w100p" /><br />
       				<span class="form_note">{$aLang.topic_create_tags_notice}</span></p>
												
					<p><label for="topic_forbid_comment"><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if}/> 
					&mdash; {$aLang.topic_create_forbid_comment}</label><br />
					<span class="form_note">{$aLang.topic_create_forbid_comment_notice}</span></p>

					{if $oUserCurrent->isAdministrator()}
						<p><label for="topic_publish_index"><input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if}/> 
						&mdash; {$aLang.topic_create_publish_index}</label><br />
						<span class="form_note">{$aLang.topic_create_publish_index_notice}</span></p>
					{/if}
					
					{hook run='form_add_topic_topic_end'}					
					<p class="buttons">
					<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" class="right" />
					<input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="$('text_preview').getParent('div').setStyle('display','block'); ajaxTextPreview('topic_text',false); return false;" />&nbsp;
					<input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
					</p>
				</form>

			</div>
*}

{include file='footer.tpl'}

