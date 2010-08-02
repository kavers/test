<?php
/*---------------------------------------------------------------------------
* @Module Name: MySearch
* @Module Id: ls_mysearch
* @Module URI: http://livestreet.ru/addons/74/
* @Description: Simple Search via MySQL (without Sphinx) for LiveStreet
* @Version: 1.1.34
* @Author: aVadim
* @Author URI: 
* @LiveStreet Version: 0.3.1
* @File Name: ActionMysearch.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

require_once(DIR_SERVER_ROOT.'/classes/lib/external/Jevix/jevix.class.php');

/**
 * "Родной" поиск без использования Spinx'а
 *
 */
class ActionMysearch extends Action {
  protected $aReq;
  protected $sPatternW='[\wа-яА-Я\.\*-]'; 	// символ слова
  protected $sPatternB='[^\wа-яА-Я\.\*-]'; 	// граница слова
  protected $sPatternX='[^\s\wа-яА-Я\*-]';	// запрещеные символы без *
  protected $sPatternXA='[^\s\wа-яА-Я-]';	// запрещеные символы, в т.ч. *
  protected $nModeOutList;
  protected $nShippetLength;
  protected $nShippetMaxLength;
  protected $nShippetOffset;
  protected $sSnippetBeforeMatch;
  protected $sSnippetAfterMatch;
  protected $sSnippetBeforeFragment;
  protected $sSnippetAfterFragment;
  protected $nSnippetMaxFragments;

  protected $bSearchStrict = true;  // Строгий поиск
  protected $bSkipAllTags=true;     // Не искать в тегах

  protected $oJevix=null; // придется выборочно "чистить" HTML-текст

  protected $bLogEnable=false;
  protected $oUser=null;
  protected $oLogs=null;

  /**
   * Инициализация модуля
   */
  public function Init() {
    $this->SetDefaultEvent('index');
    $this->Viewer_AddHtmlTitle($this->Lang_Get('search'));
    $this->nModeOutList = MYSEARCH_OUT_MODE;

    $this->nShippetLength = MYSEARCH_SNIPPET_LENGTH;
    $this->nShippetMaxLength = MYSEARCH_SNIPPET_MAXLENGTH;
    if (($this->nShippetMaxLength > 0) && ($this->nShippetMaxLength < $this->nShippetLength)) {
      $this->nShippetMaxLength = $this->nShippetLength;
    }

    $this->sSnippetBeforeMatch = MYSEARCH_SNIPPET_BEFORE_MATCH;
    $this->sSnippetAfterMatch = MYSEARCH_SNIPPET_AFTER_MATCH;
    $this->sSnippetBeforeFragment = MYSEARCH_SNIPPET_BEFORE_FRAGMENT;
    $this->sSnippetAfterFragment = MYSEARCH_SNIPPET_AFTER_FRAGMENT;
    $this->nSnippetMaxFragments = MYSEARCH_SNIPPET_MAX_FRAGMENTS;

    $this->sPatternW = MYSEARCH_CHAR_PATTERN;
    $this->sPatternB = '[^'.mb_substr($this->sPatternW, 1); // '[^\wа-яА-Я\.\*-]'; 	// граница слова
    $this->sPatternX = '[^\s'.mb_substr($this->sPatternW, 1); // '[^\s\wа-яА-Я\*-]';	// запрещеные символы без *
    $this->sPatternXA= '[^\s\*'.mb_substr($this->sPatternW, 1); // '[^\s\wа-яА-Я-]';	// запрещеные символы, в т.ч. *

    $this->bSearchStrict = MYSEARCH_STRICT;
    $this->bSkipAllTags = MYSEARCH_SKIP_ALL_TAGS;

    mb_internal_encoding("UTF-8");

    $this->oJevix = new Jevix();
    // Разрешённые теги
    if ($this->nModeOutList == MYSEARCH_OUT_TEXT_SNIPPET) {
      $this->oJevix->cfgAllowTags(array('a', 'img', 'object', 'param', 'embed'));
    } else {
      $this->oJevix->cfgAllowTags(array('a', 'img', 'object', 'param', 'embed'));
    }
    // Коротие теги типа
    $this->oJevix->cfgSetTagShort(array('img'));
    // Разрешённые параметры тегов
    $this->oJevix->cfgAllowTagParams('img', array('src', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'));
    $this->oJevix->cfgAllowTagParams('a', array('title', 'href', 'rel'));
    $this->oJevix->cfgAllowTagParams('object', array('width' => '#int', 'height' => '#int', 'data' => '#link'));
    $this->oJevix->cfgAllowTagParams('param', array('name' => '#text', 'value' => '#text'));
    $this->oJevix->cfgAllowTagParams('embed', array('src' => '#image', 'type' => '#text','allowscriptaccess' => '#text', 'allowfullscreen' => '#text','width' => '#int', 'height' => '#int', 'flashvars'=> '#text', 'wmode'=> '#text'));
    // Параметры тегов являющиеся обязательными
    $this->oJevix->cfgSetTagParamsRequired('img', 'src');
    $this->oJevix->cfgSetTagParamsRequired('a', 'href');
    // Теги которые необходимо вырезать из текста вместе с контентом
    $this->oJevix->cfgSetTagCutWithContent(array('script', 'iframe', 'style'));
    // Вложенные теги
    $this->oJevix->cfgSetTagChilds('object', 'param', false, true);
    $this->oJevix->cfgSetTagChilds('object', 'embed', false, false);
    // Отключение авто-добавления <br>
    $this->oJevix->cfgSetAutoBrMode(true);

    if (defined('MYSEARCH_LOGS_ENABLE') && MYSEARCH_LOGS_ENABLE) {
      if (defined('LOGS_VERSION') && version_compare(LOGS_VERSION, '1.2')) {
        $this->bLogEnable = true;
      }
    }

  }

  /***
   * Регистрация событий
   */
  protected function RegisterEvent() {
    $this->AddEvent('index','EventIndex');
    $this->AddEvent('topics','EventTopics');
    $this->AddEvent('comments','EventComments');
    $this->AddEvent('blogs','EventBlogs');
  }

  /**
   * Протоколирование запросов
   */
  public function OutLog($aVars=null) {
    if (!$this->bLogEnable) return;

    if (defined('MYSEARCH_LOGS_FILE') && MYSEARCH_LOGS_FILE) {$sLogFile = MYSEARCH_LOGS_FILE;}
    else {$sLogFile = 'mysearch.log';}

    if (!$this->oUser) {
      if ($sUserId=$this->Session_Get('user_id')) {
        $this->oUser=$this->User_GetUserById($sUserId);
      }
    }
    if (!$this->oUser) {$sUserLogin='*anonymous*';} else {$sUserLogin=$this->oUser->GetLogin();}

    if (!$this->oLogs) $this->oLogs=$this->Adminlogs_GetLogs();

    $path=Router::GetPathWebCurrent();
    if (strpos($path, DIR_WEB_ROOT)===0) $path = mb_substr($path, DIR_WEB_ROOT);
    $uri=$_SERVER["REQUEST_URI"];
    if (strpos($uri, DIR_WEB_ROOT)===0) $uri = mb_substr($uri, DIR_WEB_ROOT);

    $sStrLog='user=>"'.$sUserLogin.'" ip=>"'.$_SERVER["REMOTE_ADDR"].'"'."\n".
        str_repeat(' ', 22).'path=>'.$path.'"'."\n".
        str_repeat(' ', 22).'uri=>'.$uri.'"';
    if (is_array($aVars) && sizeof($aVars)) {
      foreach ($aVars as $key=>$val) {
        $sStrLog .= "\n".str_repeat(' ', 22).$key.'=>"'.$val.'"';
      }
    }

    $this->oLogs->SetLogOptions('mysearch', array('file'=>$sLogFile, 'trace'=>false));
    $this->oLogs->RecordAdd('mysearch', $sStrLog);
  }

  /**
   * Преобразование RegExp-а к стандарту PHP
   */
  protected function PreparePattern() {
    if ($this->bSearchStrict) {
      $sRegexp=$this->aReq['regexp'];
      $sRegexp=str_replace('[[:>:]]', $this->sPatternB, $sRegexp);
      $sRegexp=str_replace('[[:<:]]', $this->sPatternB, $sRegexp);

      $sRegexp='/'.$sRegexp.'/iusxUS';
    } else {
      $sRegexp='/'.$this->aReq['regexp'].'/iusxUS';
    }
    return $sRegexp;
  }

  /**
   * Подсветка текста
   */
  protected function TextHighlite($text) {
    $sRegexp=$this->PreparePattern();
    if ($this->bSearchStrict) {
      $text=preg_replace($sRegexp, $this->sSnippetBeforeMatch.'\\0'.$this->sSnippetAfterMatch, $text);
    } else {
      $text=preg_replace($this->aReq['regexp'], $this->sSnippetBeforeMatch.'\\0'.$this->sSnippetAfterMatch, $text);
    }
    return $text;
  }

  /**
   * Создание фрагмента для сниппета
   */
  protected function MakeSnippetFragment($text, $set, $pos, $len) {
    $fragment = '';
    $lenWord = $len;
    $lenText = mb_strlen($text);

    $this->nShippetOffset=floor(($this->nShippetLength - $lenWord) / 2);

    // начало фрагмена
    if ($pos < $this->nShippetOffset) {
      $fragBegin = 0;
    } else {
      $fragBegin = $pos-$this->nShippetOffset;
    }

    // конец фрагмента
    if ($pos+$lenWord+$this->nShippetOffset > $lenText) {
      $fragEnd = $lenText;
    } else {
      $fragEnd = $pos+$lenWord+$this->nShippetOffset;
    }

    // Выравнивание по границе слов
    if (($fragBegin > 0) && mb_preg_match('/'.$this->sPatternW.'+$/uisxSX', mb_substr($text, 0, $fragBegin), $m)) {
      $fragBegin-=mb_strlen($m[0][0]);
    }

    if (($fragEnd < $lenText) && mb_preg_match('/'.$this->sPatternW.'+/uisxSX', mb_substr($text, $fragEnd), $m)) {
      $fragEnd+=mb_strlen($m[0][0]) + $m[0][1];
    }

    // Обрезание по максимальной длине
    if (($this->nShippetMaxLength > 0) && (($nOver = $fragEnd - $fragBegin - $this->nShippetMaxLength) > 0)) {
      $fragBegin -= floor($nOver / 2);
      if ($fragBegin > $pos) $fragBegin = $pos;
      $fragEnd = $fragBegin + $this->nShippetMaxLength;
      if ($fragEnd < $pos + $lenWord) $fragEnd = $pos + $lenWord;
    }

    $fragment='';

    // * Укладываем слова из одного сета в один фрагмент
    $begin=$fragBegin;
    foreach ($set as $word) {
      $pos = $word['pos'];
      $fragment.=str_replace('>', '&gt;', str_replace('<', '&lt;', mb_substr($text, $begin, $pos-$begin)));
      $fragment.=$this->sSnippetBeforeMatch.$word['txt'].$this->sSnippetAfterMatch;
      $begin = $pos + $word['len'];
    }
    $fragment.=str_replace('>', '&gt;', str_replace('<', '&lt;', mb_substr($text, $begin, $fragEnd-$begin)));

    $fragment=(($fragBegin > 0)?'&hellip;':'').$fragment.(($fragEnd < $lenText)?'&hellip;':'');
    $fragment=str_replace('&lt;br/&gt;', '', $fragment);
    return $fragment;
  }

  /**
   * Создание сниппета
   */
  protected function MakeSnippet($text) {
    $sRegexp=$this->PreparePattern();
    // * Если задано, то вырезаем все теги
    if ($this->bSkipAllTags) {
      $text = strip_tags($text);
    } else {
      $text=$this->oJevix->parse($text, $aError);
      $text=str_replace('<br/>', '', $text);
    }

    $text=str_replace(' ', '  ', $text);
    if (mb_preg_match_all($sRegexp, $text, $matches, PREG_OFFSET_CAPTURE)) {
      // * Создаем набор фрагментов текста
      $snippet='';
      $fragmentSets=array();
      $fragmentSetsCount=-1;
      $count=0;
      $lastSet = array();
      $lastLen = 0;
      foreach ($matches[0] as $match) {
        $frTxt = $match[0];
        $frPos = $match[1];
        $frLen = mb_strlen($frTxt);
        // Создаем сеты фрагментов, чтобы близлежащие слова попали в один сет
        if (($fragmentSetsCount==-1) || $lastLen == 0) {
          $lastSet=array('txt'=>$frTxt, 'pos'=>$frPos, 'len'=>$frLen);
          $lastLen=$frPos + $frLen;
          $fragmentSets[++$fragmentSetsCount][] = $lastSet;
        } else {
          if (($frPos + $frLen - $lastSet['pos']) < $this->nShippetLength) {
            $fragmentSets[$fragmentSetsCount][] = array('txt'=>$frTxt, 'pos'=>$frPos, 'len'=>$frLen);
            $lastLen = $frPos + $frLen - $lastSet['pos'];
          } else {
            $lastSet=array('txt'=>$frTxt, 'pos'=>$frPos, 'len'=>$frLen);
            $lastLen=$frPos + $frLen;
            $fragmentSets[++$fragmentSetsCount][] = $lastSet;
          }
        }
      }

      foreach ($fragmentSets as $set) {
        $len = 0;
        foreach ($set as $word) {
          if ($len == 0) {$len = $word['len']; $pos = $word['pos'];}
          else {$len = $word['pos'] + $word['len'] - $pos;}
        }

        $fragments[]=$this->MakeSnippetFragment($text, $set, $pos, $len);
        if (($this->nSnippetMaxFragments > 0) && ((++$count) >= $this->nSnippetMaxFragments))
          break;
      }
      foreach ($fragments as $fragment) {
        $snippet.=$this->sSnippetBeforeFragment.$fragment.$this->sSnippetAfterFragment;
      }
    } else {
      if (mb_strlen($text) > $this->nShippetMaxLength) {
        $snippet = mb_substr($text, 0, $this->nShippetMaxLength).'&hellip;';
      } else {
        $snippet = $text;
      }
    }
    return $snippet;
  }

  /**
   * Обработка основного события
   */
  public function EventIndex() {
    $sEvent=Router::GetActionEvent();
    if ($sEvent=='comments') {
      $this->EventComments();
    } elseif ($sEvent=='blogs') {
      $this->EventBlogs();
    } else {
      $this->EventTopics();
    }
  }

  /**
   * Поиск топиков
   */
  public function EventTopics() {

    $this->aReq = $this->PrepareRequest('topics');
    $this->OutLog();
    if ($this->aReq['regexp']) {
      $aResult=$this->Mysearch_GetTopicsIdByRegexp($this->aReq['regexp'], $this->aReq['iPage'], BLOG_TOPIC_PER_PAGE, $this->aReq['params']);

      $aTopics=array();
      if ($aResult['count'] > 0) {
      /**
       * Получаем топик-объекты по списку идентификаторов
       */
        $aTopicsFound = $this->Topic_GetTopicsByArrayId($aResult['collection']);

        /**
         * Подсветка поисковой фразы в тексте или формирование сниппета
         */
        foreach($aTopicsFound AS $oTopic) {
          $aTopic=array();
          if ($this->nModeOutList==MYSEARCH_OUT_TEXT_SHORT) {
            $aTopic['getTextShort'] = $this->TextHighlite($oTopic->getTextShort());
          } elseif ($this->nModeOutList==MYSEARCH_OUT_TEXT_FULL) {
            $aTopic['getTextShort'] = $this->TextHighlite($oTopic->getText());
          } else {
            $aTopic['getTextShort'] = $this->MakeSnippet($oTopic->getText());
          }
          $aTopic['getId'] = $oTopic->getId();
          $aTopic['getDate'] = $oTopic->getDateAdd();
          $aTopic['getUrl'] = $oTopic->getUrl();
          $aTopic['getTitle'] = $oTopic->getTitle();
          $aTopic['getBlogUrlFull'] = $oTopic->getBlogUrlFull();
          $aTopic['getBlogTitle'] = $oTopic->getBlogTitle();
          $aTopic['getCountComment'] = $oTopic->getCountComment();
          $aTopic['getRating'] = $oTopic->getRating();
          $aTopic['getUserLogin'] = $oTopic->getUserLogin();
          if ($oUser=$this->User_GetUserById($oTopic->getUserId())) {
            $aTopic['getUserProfileAvatarPath'] = $oUser->GetProfileAvatarPath(24);
          } else {
            $aTopic['getUserProfileAvatarPath']='';
          }
          $aTopics[]=$aTopic;
        }
      }
    } else {
      $aResult['count']=0;
      $aTopics=array();
    }

    if ($this->bLogEnable) {
      $this->oLogs->RecordAdd('mysearch', array('q'=>$this->aReq['q'], 'result'=>'topics:'.$aResult['count']));
      $this->oLogs->RecordEnd('mysearch', true);
    }

    $aPaging=$this->Viewer_MakePaging($aResult['count'], $this->aReq['iPage'], BLOG_TOPIC_PER_PAGE, 4,
        DIR_WEB_ROOT.'/'.ROUTE_PAGE_MYSEARCH.'/topics', array('qq'=>$this->aReq['q']));
    /**
     *  Отправляем данные в шаблон
     */
    $this->SetTemplateAction('results');
    $this->Viewer_AddHtmlTitle($this->aReq['q']);
    $this->Viewer_Assign('bIsResults', $aResult['count']);
    $this->Viewer_Assign('aReq', $this->aReq);
    $this->Viewer_Assign('aRes', $aResult);
    $this->Viewer_Assign('aTopics', $aTopics);
    $this->Viewer_Assign('aPaging', $aPaging);
    $this->Viewer_Assign('ROUTE_PAGE_MYSEARCH', ROUTE_PAGE_MYSEARCH);
  } // public function EventTopics() //

  /**
   * Поиск комментариев
   */
  public function EventComments() {

    $this->aReq = $this->PrepareRequest('comments');

    $this->OutLog();
    if ($this->aReq['regexp']) {
      $aResult=$this->Mysearch_GetCommentsIdByRegexp($this->aReq['regexp'], $this->aReq['iPage'], BLOG_TOPIC_PER_PAGE, $this->aReq['params']);

      if($aResult['count']==0) {
        $aComments=array();
      } else {
      /**
       * Получаем объекты по списку идентификаторов
       */
        $aComments = $this->Comment_GetCommentsByArrayId($aResult['collection']);

        //подсветка поисковой фразы
        foreach($aComments AS $oComment) {
          if ($this->nModeOutList!=MYSEARCH_OUT_TEXT_SNIPPET) {
            $oComment->setText($this->TextHighlite($oComment->getText()));
          } else {
            $oComment->setText($this->MakeSnippet($oComment->getText()));
          }
        }
      }
    } else {
      $aResult['count']=0;
      $aComments=array();
    }

    // * Логгируем результаты, если требуется
    if ($this->bLogEnable) {
      $this->oLogs->RecordAdd('mysearch', array('q'=>$this->aReq['q'], 'result'=>'comments:'.$aResult['count']));
      $this->oLogs->RecordEnd('mysearch', true);
    }

    $aPaging=$this->Viewer_MakePaging($aResult['count'], $this->aReq['iPage'], BLOG_TOPIC_PER_PAGE, 4,
        DIR_WEB_ROOT.'/'.ROUTE_PAGE_MYSEARCH.'/comments', array('qq'=>$this->aReq['q']));
    // *  Отправляем данные в шаблон
    $this->SetTemplateAction('results');
    $this->Viewer_AddHtmlTitle($this->aReq['q']);
    $this->Viewer_Assign('bIsResults', $aResult['count']);
    $this->Viewer_Assign('aReq', $this->aReq);
    $this->Viewer_Assign('aRes', $aResult);
    $this->Viewer_Assign('aComments', $aComments);
    $this->Viewer_Assign('aPaging', $aPaging);
    $this->Viewer_Assign('ROUTE_PAGE_MYSEARCH', ROUTE_PAGE_MYSEARCH);
  } // public function EventComments() //

  /**
   * Поиск блогов
   */
  public function EventBlogs() {

    $this->aReq = $this->PrepareRequest('blogs');

    $this->OutLog();
    if ($this->aReq['regexp']) {
      $aResult=$this->Mysearch_GetBlogsIdByRegexp($this->aReq['regexp'], $this->aReq['iPage'], BLOG_TOPIC_PER_PAGE, $this->aReq['params']);

      if($aResult['count']==0) {
        $aBlogs=array();
      } else {
        // * Получаем объекты по списку идентификаторов
        $aBlogs = $this->Mysearch_GetBlogsByArrayId($aResult['collection']);

        //подсветка поисковой фразы
        foreach($aBlogs AS $oBlog) {
          if ($this->nModeOutList!=MYSEARCH_OUT_TEXT_SNIPPET) {
            $oBlog->setDescription($this->TextHighlite($oBlog->getDescription()));
          } else {
            $oBlog->setDescription($this->MakeSnippet($oBlog->getDescription()));
          }
        }
      }
    } else {
      $aResult['count']=0;
      $oBlog=array();
    }

    // * Логгируем результаты, если требуется
    if ($this->bLogEnable) {
      $this->oLogs->RecordAdd('mysearch', 
          array('q'=>$this->aReq['q'], 'result'=>'blogs:'.$aResult['count']));
      $this->oLogs->RecordEnd('mysearch', true);
    }

    $aPaging=$this->Viewer_MakePaging($aResult['count'], $this->aReq['iPage'], BLOG_TOPIC_PER_PAGE, 4,
        DIR_WEB_ROOT.'/'.ROUTE_PAGE_MYSEARCH.'/blogs', array('qq'=>$this->aReq['q']));
    // *  Отправляем данные в шаблон
    $this->SetTemplateAction('results');
    $this->Viewer_AddHtmlTitle($this->aReq['q']);
    $this->Viewer_Assign('bIsResults', $aResult['count']);
    $this->Viewer_Assign('aReq', $this->aReq);
    $this->Viewer_Assign('aRes', $aResult);
    $this->Viewer_Assign('aBlogs', $aBlogs);
    $this->Viewer_Assign('aPaging', $aPaging);
    $this->Viewer_Assign('ROUTE_PAGE_MYSEARCH', ROUTE_PAGE_MYSEARCH);
  } // public function EventBlogs //

  /**
   * Разбор запроса
   */
  private function PrepareRequest($sType=null) {
    $sRequest = getRequest('q');
    if (!$sRequest) $sRequest = getRequest('qq');

    // * Иногда ломается кодировка, напр., если ввели поиск в адресной строке браузера
    // * Пытаемся восстановить по основной кодировке браузера
    if (!mb_check_encoding($sRequest)) {
      list($sCharset) = explode(',', $_SERVER["HTTP_ACCEPT_CHARSET"]);
      $sQueryString=mb_convert_encoding($_SERVER['QUERY_STRING'], 'UTF-8', $sCharset);
      $sRequest = mb_convert_encoding($sRequest, 'UTF-8', $sCharset);
    }
    if ($sRequest) {
      $sRequest = preg_replace("/(\s{2,})/", " ", $sRequest);
      $sRequest = preg_replace("/[\*\s]{2,}/", "* ", $sRequest);
      $sRequest = preg_replace("/(\*{2,})/", "*", $sRequest);
    }

    $aReq['q'] = $sRequest;
    $aReq['regexp'] = trim(mb_strtolower($aReq['q']));

    // * Проверка длины запроса
    if (!func_check($aReq['regexp'], 'text', MYSEARCH_MIN_LENGTH, MYSEARCH_MAX_LENGTH)) {
      $this->Message_AddError(sprintf($this->Lang_Get('search_err_length'), MYSEARCH_MIN_LENGTH, MYSEARCH_MAX_LENGTH));
      $aReq['regexp']='';
    }

    /**
     * Проверка длины каждого слова в запросе
     * Хотя бы одно слово должно быть больше минимальной длины
     * Слова меньше минимальной длины исключаем из поиска
     */
    if ($aReq['regexp']) {
      $aWords = explode(' ', $aReq['regexp']);
      $nErr=0; $sStr='';
      foreach ($aWords as $sWord) {
        if (!func_check($sWord, 'text', MYSEARCH_MIN_LENGTH, MYSEARCH_MAX_LENGTH)) {
          $nErr+=1;
        } else {
          if ($sStr) $sStr.=' '; $sStr.=$sWord;
        }
      }
      if ($nErr == sizeof($aWords)) {
        $this->Message_AddError(sprintf($this->Lang_Get('search_err_length_word'), MYSEARCH_MIN_LENGTH, MYSEARCH_MAX_LENGTH));
        $aReq['regexp']='';
      } else {
        $aReq['regexp'] = $sStr;
      }
    }

    // * Если все нормально, формируем выражение для поиска
    if ($aReq['regexp']) {
      if ($this->bSearchStrict) {
      /**
       * Проверка на "лишние" символы, оставляем только "слова"
       * На месте "небукв" оставляем пробелы
       */
        $aReq['regexp'] = preg_replace('/'.$this->sPatternXA.'/iusxS', ' ', $aReq['regexp']);
        $aReq['regexp'] = trim(preg_replace("/(\s{2,})/", " ", $aReq['regexp']));
        // * Если после "чистки" что-то осталось, то продолжаем дальше
        if ($aReq['regexp']) {
          $aReq['regexp'] = str_replace('* *', '|', $aReq['regexp']);
          $aReq['regexp'] = str_replace('* ', '|[[:<:]]', $aReq['regexp']);
          $aReq['regexp'] = str_replace(' *', '[[:>:]]|', $aReq['regexp']);
          $aReq['regexp'] = str_replace(' ', '[[:>:]]|[[:<:]]', $aReq['regexp']);

          if (mb_substr($aReq['regexp'], 0, 1)=='*') $aReq['regexp']=mb_substr($aReq['regexp'], 1);
          else $aReq['regexp']='[[:<:]]'.$aReq['regexp'];

          if (mb_substr($aReq['regexp'], -1)=='*') $aReq['regexp']=mb_substr($aReq['regexp'], 0, mb_strlen($aReq['regexp'])-1);
          else $aReq['regexp']=$aReq['regexp'].'[[:>:]]';
        }
      } else {
        $aReq['regexp'] = preg_replace('/'.$this->sPatternXA.'/u', '', $aReq['regexp']);
        $aReq['regexp'] = trim(preg_replace("/(\s{2,})/", " ", $aReq['regexp']));
        $aReq['regexp'] = str_replace(' ', '|', $aReq['regexp']);
      }
    }

    $aReq['params']['bSkipTags'] = false;
    if ($sType) {$aReq['sType']=$sType;} else {$aReq['sType'] = 'topics';}
    // * Определяем текущую страницу вывода результата
    $aReq['iPage'] = intval(preg_replace('#^page(\d+)$#', '\1', $this->getParam(0)));
    if(!$aReq['iPage']) { $aReq['iPage'] = 1; }
    // *  Передача данных в шаблонизатор
    $this->Viewer_Assign('aReq', $aReq);
    return $aReq;
  }

}

/***
 * Добавляем аналоги ф-ций preg_*, по-человечески обрабатывающие
 * кириллицу в UTF-8
 */
if (!function_exists('mb_preg_match_all')) {

  function mb_preg_match_fix($func_all, $ps_pattern, $ps_subject, &$pa_matches,
      $pn_flags = PREG_OFFSET_CAPTURE, $pn_offset = 0, $ps_encoding = NULL) {
    if (is_null($ps_encoding))
      $ps_encoding = mb_internal_encoding();

    $pn_offset = strlen(mb_substr($ps_subject, 0, $pn_offset, $ps_encoding));
    if ($func_all==0) {
      $ret = preg_match($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);
      if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
        foreach($pa_matches as &$ha_match)
          $ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
    } else {
      $ret = preg_match_all($ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset);
      if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
        foreach($pa_matches as &$ha_match)
          foreach($ha_match as &$ha_match)
            $ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
    }

    return $ret;
    if ($ret && ($pn_flags & PREG_OFFSET_CAPTURE))
      foreach($pa_matches as &$ha_match)
        foreach($ha_match as &$ha_match)
          $ha_match[1] = mb_strlen(substr($ps_subject, 0, $ha_match[1]), $ps_encoding);
    return $ret;
  }

  function mb_preg_match($ps_pattern, $ps_subject, &$pa_matches, $pn_flags = PREG_OFFSET_CAPTURE, $pn_offset = 0, $ps_encoding = NULL) {
    return mb_preg_match_fix(0, $ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset, $ps_encoding);
  }

  function mb_preg_match_all($ps_pattern, $ps_subject, &$pa_matches, $pn_flags = PREG_OFFSET_CAPTURE, $pn_offset = 0, $ps_encoding = NULL) {
    return mb_preg_match_fix(1, $ps_pattern, $ps_subject, $pa_matches, $pn_flags, $pn_offset, $ps_encoding);
  }

}
// EOF