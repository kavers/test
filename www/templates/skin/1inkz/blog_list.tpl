     <li id="vip_blogs" class="block2 green">
        <div class="title"><a href="/" class="link"><h1>Блоги</h1></a>
        <img src="{cfg name='path.static.skin'}/img/arrow.gif" width="7" height="12" alt="arrow" title="arrow" class="arrow"/>
        <a href="{router page='blogs'}" class="link"><h1>Сообщества</h1></a>
        </div>
         <ul class="gradient">
           <li class="first2"><a href="">Последние</a></li>
           <li><a href="">Популярные</a></li>
           <li><a href="">Обсуждаемые</a></li>
           <li><a class="close" href="">По языку записей</a></li>
           <li><a class="close" href="">По рейтингу</a></li>
           <li class="right"><a class="more_menu close" href="">За неделю</a>
                 <ul class="all_menu">
                 <li><a href="#">Все</a></li>
                 <li><a href="#">За день</a></li>
                 <li><a href="#">За неделю</a></li>
                 <li><a href="#">За месяц</a></li>
                 <li><a href="#">За год</a></li>
              </ul>
           </li>
        </ul>

        <div class="block_content">
           <ul class="table_list dop">
        {foreach from=$aBlogs item=oBlog}
        {assign var="oUserOwner" value=$oBlog->getOwner()}
              <li>
                 <a href="{router page='blog'}{$oBlog->getUrl()}/"><img src="{$oBlog->getAvatarPath(48)}" alt="" /></a>
                 <a class="item_title" href="{router page='blog'}{$oBlog->getUrl()}/" class="title {if $oBlog->getType()=='close'}close{/if}">{$oBlog->getTitle()|escape:'html'}</a>
                 {*<strong>8:02</strong>*}
                 <p>{date_format date=$oBlog->getDateAdd()}</p>
                 <div>{$aLang.blogs_rating}: {$oBlog->getRating()}</div>
                 {*<div class="rating red">круто</div>*}
	         {*<div class="vipblog_info">
                    <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico8.png" width="24" height="23" alt="" title=""/></a>
                    <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                    <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico6.png" width="24" height="23" alt="" title=""/></a>
                    <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico7.png" width="24" height="23" alt="" title=""/></a>
        	 </div>
             *}
              </li>
         {/foreach}
           </ul>
           
           {include file='paging.tpl' aPaging=`$aPaging`}
           
        <div class="block_bottom3"></div>
        </div>
     </li>
      
{*				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.blogs_title}</td>
							{if $oUserCurrent}
							<td class="join-head"><img src="{cfg name='path.static.skin'}/images/join-head.gif" alt="" /></td>
							{/if}
							<td class="readers">{$aLang.blogs_readers}</td>														
							<td class="rating">{$aLang.blogs_rating}</td>
						</tr>
					</thead>
					
					<tbody>
						{foreach from=$aBlogs item=oBlog}
						{assign var="oUserOwner" value=$oBlog->getOwner()}
						<tr>
							<td class="name">
								<a href="{router page='blog'}{$oBlog->getUrl()}/"><img src="{$oBlog->getAvatarPath(24)}" alt="" /></a>
								<a href="{router page='blog'}{$oBlog->getUrl()}/" class="title {if $oBlog->getType()=='close'}close{/if}">{$oBlog->getTitle()|escape:'html'}</a><br />
								{$aLang.blogs_owner}: <a href="{router page='profile'}{$oUserOwner->getLogin()}/" class="author">{$oUserOwner->getLogin()}</a>
							</td>
							{if $oUserCurrent}
							<td class="join {if $oBlog->getUserIsJoin()}active{/if}">
								{if $oUserCurrent->getId()!=$oBlog->getOwnerId() and $oBlog->getType()=='open'}
									<a href="#" onclick="ajaxJoinLeaveBlog(this,{$oBlog->getId()}); return false;"></a>
								{/if}
							</td>
							{/if}
							<td id="blog_user_count_{$oBlog->getId()}" class="readers">{$oBlog->getCountUser()}</td>													
							<td class="rating"><strong>{$oBlog->getRating()}</strong></td>
						</tr>
						{/foreach}
					</tbody>
				</table>
*}
