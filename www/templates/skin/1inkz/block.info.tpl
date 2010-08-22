{assign var="oUserOwner" value=$oBlog->getOwner()}
<!--  Блок Сведения о новости -->
     <li id="about_news" class="block green">
        <div class="title"><a href="#111" class="link"><h1>Сведения</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content">
        <ul class="gradient">
           <li class="first3"><a href="">Блог</a></li>
           <li><a href="#">Профиль</a></li>
           <li><a href="#">Записи друзей</a></li>
           <li><a href="" class="more_menu close">ещё</a>
              <ul class="all_menu">
                 <li><a href="#">Crunk</a></li>
                 <li><a href="#">Dance</a></li>
                 <li><a href="#">Drum 'N' Bass</a></li>
                 <li><a href="#">R'n'B</a></li>
                 <li><a href="#">Rap</a></li>
                 <li><a href="#">Soundtrack</a></li>
                 <li><a href="#">Вокал</a></li>
                 <li><a href="#">Гимны</a></li>
                 <li><a href="#">Джаз</a></li>
                 <li><a href="#">Казахская музыка</a></li>
                 <li><a href="#">Классическая музыка</a></li>
                 <li><a href="#">Поп</a></li>
                 <li><a href="#">Ретро</a></li>
                 <li><a href="#">Рок</a></li>
                 <li><a href="#">Рок-н-ролл</a></li>
              </ul>
           </li>
        </ul>
        <ul class="descriptions">
           <li class="descr1">
              <h2>Опубликовал</h2>
              <a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(48)}" width="47" height="44" alt="avatar" title="avatar" /></a>
              <a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
              <span>10 дней назад</span>

              <div class="user_info">
                 <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico5.png" width="24" height="23" alt="" title=""/></a>
                 <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico6.png" width="24" height="23" alt="" title=""/></a>
                 <a href="#"><img src="{cfg name='path.static.skin'}/img/comment_ico7.png" width="24" height="23" alt="" title=""/></a>
              </div>
           </li>
           <li class="descr1">
              <h2>Описание и теги</h2>
              <p class="lt">Язык записи <strong>Русский</strong>&nbsp;&nbsp; Место <strong>Питер</strong>&nbsp;&nbsp; Настроение <strong>Норма</strong>&nbsp;&nbsp; Музыка <strong>Britney Spears - Piece of Me</strong></p>
				<ul class="tags">
                    {foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
					{/foreach}
              </ul>
           </li>
           <li class="descr1">
              <h2>Статистика</h2>
              <div class="rating_title">Рейтинг</div><div class="cur_rating red"><a href="javascript:void(0);" id="cur_name">баян</a></div>
              <div id="rating_picker">
                <img src="{cfg name='path.static.skin'}/img/palitra.png" width="216" height="216" alt="рейтинг" title="рейтинг" usemap="#palitra_Map" />
				<map name="palitra_Map">
				<area shape="poly" alt="Классика" coords="0,54, 107,107, 52,0" href="#classic" onClick="Palitra(event, 'классика')" />
				<area shape="poly" alt="Отстой" coords="52,216, 107,108, 0,162" href="#fi" onClick="Palitra(event, 'отстой')" />
				<area shape="poly" alt="ОК" coords="163,216, 108,107, 216,162" href="#ok" onClick="Palitra(event, 'ок')" />
				<area shape="poly" alt="Супер" coords="163,0, 108,108, 216,54" href="#super" onClick="Palitra(event, 'супер')" />
				<area shape="poly" alt="Баян" coords="0,161, 107,108, 0,54" href="#bayan" onClick="Palitra(event, 'баян')" />
				<area shape="poly" alt="Скучно" coords="163,216, 108,108, 52,216" href="#border" onClick="Palitra(event, 'скучно')" />
				<area shape="poly" alt="Свежак" coords="216,55, 108,108, 216,161" href="#fresh" onClick="Palitra(event, 'свежак')" />
				<area shape="poly" alt="Круто" coords="108,108, 163,0, 52,0" href="#cool" onClick="Palitra(event, 'круто')" />
				</map>
                <div id="rating_title" >Свежак</div>
                <div class="r_top" onClick="Palitra(event, 'круто')">круто</div>
                <div class="r_bottom" onClick="Palitra(event, 'скучно')">скучно</div>
                <div class="r_left" onClick="Palitra(event, 'баян')">баян</div>
                <div class="r_right" onClick="Palitra(event, 'свежак')">свежак</div>
              </div>
              <div id="circle"></div>
              <p>Просмотров <strong>56778</strong>&nbsp;Оценок <strong>1048</strong><br />
              Обсуждают <strong>48</strong></p>
           </li>
        </ul>
        <div class="block_bottom"></div>
        </div>
     </li>
<!--  Блок Сведения о новости -->


<li id="about_author" class="block green">
        <div class="title"><a href="#111" class="link"><h1>{$aLang.block_blogs}</h1></a><a href="#" class="close_block"><img src="{cfg name='path.static.skin'}/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content block blogs">
        <ul class="gradient block-nav">
           <li class="active"><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a></li>
            {if $oUserCurrent}
                <li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
                <li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a></li>
            {/if}
        </ul>
        
        <div class="block-content">
        {literal}
            <script language="JavaScript" type="text/javascript">
            var lsBlockBlogs;
            window.addEvent('domready', function() {       
                lsBlockBlogs=new lsBlockLoaderClass();
            });
            </script>
        {/literal}
            {$sBlogsTop}
        </div>
        <div class="right"><a href="{router page='blogs'}">{$aLang.block_blogs_all}</a></div>
        <div class="block_bottom"></div>
        </div>
     </li>

{*			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.block_blogs}</h1>
					
					<ul class="block-nav">
						<li class="active"><strong></strong><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a>{if !$oUserCurrent}<em></em>{/if}</li>
						{if $oUserCurrent}
							<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
							<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a><em></em></li>
						{/if}
					</ul>
					
					<div class="block-content">
					{literal}
						<script language="JavaScript" type="text/javascript">
						var lsBlockBlogs;
						window.addEvent('domready', function() {       
							lsBlockBlogs=new lsBlockLoaderClass();
						});
						</script>
					{/literal}
					{$sBlogsTop}
					</div>
					
					<div class="right"><a href="{router page='blogs'}">{$aLang.block_blogs_all}</a></div>

					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
*}
