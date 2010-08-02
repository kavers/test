
<div id="footer">
     <div class="title">
        <h1>Настроить</h1>
        <a href="javascript:void(0)"><img class="tools_button" src="{cfg name='path.static.skin'}/img//minus.gif" width="18" height="18" alt="Свернуть" title="Свернуть"/></a>
        <a href="javascript:void(0)"><img class="recover_button" src="{cfg name='path.static.skin'}/img//recover.gif" width="18" height="18" alt="Восстановление настроек" title="Восстановление настроек"/></a>
        <a href="javascript:void(0)"><img class="help_button" src="{cfg name='path.static.skin'}/img//help.gif" width="18" height="18" alt="Помощь" title="Помощь"/></a>

     </div>
     <div id="tools">
        <ul>
          <li class="dark_green"><input checked type="checkbox" value="" name="pop_blogs"/>Популярные блогеры</li>
          <li class="green"><input checked type="checkbox" value="" name="whats_news"/>Что нового в блогах</li>
          <li class="green"><input checked type="checkbox" value="" name="community"/>Сообщества</li>
          <li class="green"><input checked type="checkbox" value="" name="blog_experts"/>Эксперты блогосферы</li>
          <li class="dark_green"><input checked type="checkbox" value="" name="celebrity"/>Знаменитости</li>
        </ul>
     </div>
     <div class="comments">Одной из наших приоритетных целей является создание полезных пользователю интернет-проектов на рынке Казнета. Первый Казахстанский - портал, где каждый найдет для себя что-то интересное. Мы отбираем для вас лучшее видео, самые смешные приколы, оригинальные обои для рабочего стола, свежие программы, популярные книги, новую музыку и многое другое.  <a href="#">Узнать больше</a></div>
     <div class="search_counters">
        <form action="/search/" method="post" id="search_bottom" name="search_bottom">
           <input type="text" value="">
           <ul>
              <li><a href="javascript:void(0)" onclick="comboboxShow(this)">Везде</a>
                <ul>
                  <li><a href="">Форум</a></li>
                </ul>
              </li>
           </ul>
           <a href=""><img src="{cfg name='path.static.skin'}/img//search_button2.gif" width="28" height="26" alt="Искать" title="Искать"/></a>
        </form>
        <ul class="counters">
           <li><a href=""><img src="{cfg name='path.static.skin'}/img//1in_ico.gif" width="16" height="16" alt="1in.kz" title="1in.kz"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img//facebook_ico.gif" width="14" height="14" alt="Facebook" title="Facebook"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img//twitter_ico.gif" width="14" height="16" alt="Twitter" title="Twitter"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img//b_ico.gif" width="16" height="16" alt="В контакте" title="В контакте"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img//zero_ico.gif" width="88" height="31" alt="Zero" title="Zero"/></a></li>
           <li><a href=""><img src="{cfg name='path.static.skin'}/img//counter_ico.gif" width="103" height="31" alt="Kaznet" title="Kaznet"/></a></li>
        </ul>
     </div>
  </div>
  <div id="copyright">
     <a href="#"><img src="{cfg name='path.static.skin'}/img//kaznet_logo.gif" width="60" height="49" alt="Kaznet media" title="Kaznet media"/></a>
     &copy;&nbsp;2009-2010&nbsp;&laquo;Первый казахстанский&raquo;
     <ul>
        <li><a href="">О проекте</a></li>
        <li>|</li>
        <li><a href="">Блог</a></li>
        <li>|</li>
        <li><a href="">Контактная информация</a></li>
        <li>|</li>
        <li><a href="">Реклама на сайте</a></li>
     </ul>
  </div>
</div>

<script type="text/javascript" src="/js/jquery-ui-personalized-1.6rc2.min.js"></script>
<script type="text/javascript" src="/js/inettuts.js"></script>

{hook run='body_end'}
</body>
</html>