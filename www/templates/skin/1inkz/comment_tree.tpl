{*
<script type="text/javascript" src="{cfg name='path.static.skin'}/js/comments.js"></script>
*}
{if $oUserCurrent}
<!-- Добавить комментарий авторизованный -->
     <li id="add_comments2" class="block2 dark_green">
        <div class="title"><a href="#111" class="link"><h1>Добавить комментарий</h1></a><a href="#" class="close_block2"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="20" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
           <div class="add_comment">
						<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
							{hook run='form_add_comment_begin'}
    						<textarea name="comment_text" id="form_comment_text" style="width: 100%; height: 100px;"></textarea>
    						{hook run='form_add_comment_end'}
    						{*<input type="submit" class="submit" name="submit_preview" value="{$aLang.comment_preview}" onclick="lsCmtTree.preview($('form_comment_reply').getProperty('value')); return false;" />&nbsp;*}
    						<input type="submit" class="submit" name="submit_comment" value="" onclick="lsCmtTree.addComment('form_comment',{$iTargetId},'{$sTargetType}'); return false;">    	
    						<input type="hidden" name="reply" value="" id="form_comment_reply">
    						<input type="hidden" name="cmt_target_id" value="{$iTargetId}">
    					</form>
           </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
<!--  Добавить комментарий авторизованный -->
{else}
<!-- Добавить комментарий  -->
     <li id="add_comments" class="block_hide">
        <div class="title_white"><a href="#111" class="link"><h1 class="white">Добавить комментарий</h1></a><a href="#" class="close_block2"><img src="{cfg name='path.static.skin'}/img/minus2.gif" width="20" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <p><a href="{router page='registration'}">Создайте учётную запись</a> или <a href="{router page='login'}">войдите</a>, чтоб разместить комментарий</p>
        </div>
     </li>
<!-- Добавить комментарий  -->
{/if}
{*
<!--  Комментарии -->
     <li id="comments_list" class="block2 dark_green">
        <div class="title"><a href="#111" class="link"><h1>Комментарии</h1></a><a href="#" class="close_block2"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="20" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
           <div id="comments">
				{*assign var="nesting" value="-1"}
				{foreach from=$aComments item=oComment name=rublist}
					{assign var="cmtlevel" value=$oComment->getLevel()}
					{if $cmtlevel>$oConfig->GetValue('module.comment.max_tree')}
						{assign var="cmtlevel" value=$oConfig->GetValue('module.comment.max_tree')}
					{/if}
   					{*if $nesting < $cmtlevel}        
    				{elseif $nesting > $cmtlevel}    	
        				{section name=closelist1  loop=`$nesting-$cmtlevel+1`}</div></div>{/section}
    				{elseif not $smarty.foreach.rublist.first}
        				</div></div>
    				{/if}
    				 
    				<div class="comment" id="comment_id_{$oComment->getId()}">
    				    				
    					{include file='comment.tpl'}      												
							  
    				{assign var="nesting" value=$cmtlevel}    
    				{if $smarty.foreach.rublist.last}
        				{section name=closelist2 loop=`$nesting+1`}</div></div>{/section}    
    				{/if}
				{/foreach}
                
           <dl class="comment_item">
              <dt><a href="#"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="47" height="44" alt="avatar" title="avatar"/></a></dt>
              <dd>
                 <p>на старкрафте музло из третьего фоллаута. чет типа everithing goes. мне нравится))</p>
                 <p class="author"><a href="#">YakovDub</a> 10 минут назад</p>
                 <div class="rating red">круто</div>
              </dd>
           </dl>
           <dl class="comment_item level2">
              <dt><a href="#"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="47" height="44" alt="avatar" title="avatar"/></a></dt>
              <dd>
                 <p>на старкрафте музло из третьего фоллаута. чет типа everithing goes. мне нравится))</p>
                 <p class="author"><a href="#">YakovDub</a> 10 минут назад</p>
                 <div class="rating red">круто</div>
              </dd>
           </dl>
           <dl class="comment_item level3">
              <dt><a href="#"><img src="{cfg name='path.static.skin'}/img/avatar.gif" width="47" height="44" alt="avatar" title="avatar"/></a></dt>
              <dd>
                 <p>на старкрафте музло из третьего фоллаута. чет типа everithing goes. мне нравится))</p>
                 <p class="author"><a href="#">YakovDub</a> 10 минут назад</p>
                 <div class="rating red">круто</div>
              </dd>
           </dl>
           </div>
        <div class="block_bottom3"></div>
        </div>
     </li>
<!--  Комментарии -->
*}

<script type="text/javascript" src="{cfg name='path.static.skin'}/js/comments.js"></script>
{*
			<!-- Comments -->
			<div class="comments">
				{if $oUserCurrent}
				<div class="update" id="update">
					<div class="tl"></div>
					<div class="wrapper">
						<div class="refresh">
							<img class="update-comments" id="update-comments" alt="" src="{cfg name='path.static.skin'}/images/update.gif" onclick="lsCmtTree.responseNewComment({$iTargetId},'{$sTargetType}',this); return false;"/>
						</div>
						<div class="new-comments" id="new-comments" style="display: none;" onclick="lsCmtTree.goNextComment();">							
						</div>
					</div>
					<div class="bl"></div>
				</div>
				{/if}
				
				<!-- Comments Header -->
				<div class="header">
					<h3>{$aLang.comment_title} (<span id="count-comments">{$iCountComment}</span>)</h3>
					<a name="comments" ></a>
					{if $sTargetType=='topic'}
					<a href="{router page='rss'}comments/{$iTargetId}/" class="rss">RSS</a>
					{/if}
					<a href="#" onclick="lsCmtTree.collapseNodeAll(); return false;" onfocus="blur();">{$aLang.comment_collapse}</a> /
					<a href="#" onclick="lsCmtTree.expandNodeAll(); return false;" onfocus="blur();">{$aLang.comment_expand}</a>
				</div>
				<!-- /Comments Header -->			
	*}			
				{literal}
				<script language="JavaScript" type="text/javascript">
					window.addEvent('domready', function() {
						{/literal}
						lsCmtTree.setIdCommentLast({$iMaxIdComment});
						{literal}
					});					
				</script>
				{/literal}

<!--  Комментарии -->

     <li id="comments_list" class="block2 dark_green {if !$aComments}hidden{/if}">
        <div class="title"><a href="#111" class="link"><h1>Комментарии</h1></a><a href="#" class="close_block2"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="20" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
           <div id="comments">
				
				{assign var="nesting" value="-1"}
				{foreach from=$aComments item=oComment name=rublist}
					{assign var="cmtlevel" value=$oComment->getLevel()}
					{if $cmtlevel>$oConfig->GetValue('module.comment.max_tree')}
						{assign var="cmtlevel" value=$oConfig->GetValue('module.comment.max_tree')}
					{/if}
   					{*if $nesting < $cmtlevel}        
    				{elseif $nesting > $cmtlevel}    	
        				{section name=closelist1  loop=`$nesting-$cmtlevel+1`}</div></div>{/section}
    				{elseif not $smarty.foreach.rublist.first}
        				</div></div>
    				{/if*}
    				 
    				<div class="comment" id="comment_id_{$oComment->getId()}">

  					{include file='comment.tpl'}      												
                    </div>
    				{assign var="nesting" value=$cmtlevel}    
    				{*if $smarty.foreach.rublist.last}
        				{section name=closelist2 loop=`$nesting+1`}</div></div>{/section}    
    				{/if*}
				{/foreach}
				
				<span id="comment-children-0"></span>				
				<br>
           </div>
        <div class="block_bottom3"></div>
        </div>
     </li>	
     {*
				{if $bAllowNewComment}
					{$sNoticeNotAllow}
				{else}
					{if $oUserCurrent}
						<h3 class="reply-title"><a href="javascript:lsCmtTree.toggleCommentForm(0);">{$sNoticeCommentAdd}</a></h3>						
						<div class="comment"><div class="content"><div class="text" id="comment_preview_0" style="display: none;"></div></div></div>
						<div style="display: block;" id="reply_0" class="reply">						
						{if !$oConfig->GetValue('view.tinymce')}
            					<div class="panel_form" style="background: #eaecea; margin-top: 2px;">       	 
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>	 			
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>	 			
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>	 			
	 								&nbsp;
	 								<a href="#" onclick="lsPanel.putTagUrl('form_comment_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
	 								<a href="#" onclick="lsPanel.putQuote('form_comment_text'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/quote.gif" width="20" height="20" title="{$aLang.panel_quote}"></a>
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
	 							</div>
	 					{/if}
						<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
							{hook run='form_add_comment_begin'}
    						<textarea name="comment_text" id="form_comment_text" style="width: 100%; height: 100px;"></textarea>
    						{hook run='form_add_comment_end'}
    						<input type="submit" name="submit_preview" value="{$aLang.comment_preview}" onclick="lsCmtTree.preview($('form_comment_reply').getProperty('value')); return false;" />&nbsp;
    						<input type="submit" name="submit_comment" value="{$aLang.comment_add}" onclick="lsCmtTree.addComment('form_comment',{$iTargetId},'{$sTargetType}'); return false;">    	
    						<input type="hidden" name="reply" value="" id="form_comment_reply">
    						<input type="hidden" name="cmt_target_id" value="{$iTargetId}">
    					</form>
						</div>
					{else}
						{$aLang.comment_unregistered}<br>
					{/if}
				{/if}				
			</div>
            *}
			<!-- /Comments -->
            
            
