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
* @File Name: Mysearch.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once('mapper/Mysearch.mapper.class.php');

/**
 * "Родной" поиск без использования Spinx'а
 *
 */
class LsMysearch extends Module {
  private $oMapper;

  /**
   * Инициализация модуля
   *
   */
  public function Init() {
    $this->oMapper=new Mapper_Mysearch($this->Database_GetConnect());
  }

  /**
   * Получает список топиков по регулярному выражению (поиск)
   *
   * @param unknown_type $sRegexp
   * @param unknown_type $iPage
   * @param unknown_type $iPerPage
   * @return unknown
   */
  public function GetTopicsIdByRegexp($sRegexp, $iPage, $iPerPage, $aParams=array()) {
    $sTag = md5(serialize($sRegexp).serialize($aParams));
    $sCacheTag='mysearch_topics_'.$sTag.'_'.$iPage.'_'.$iPerPage;
    if (false === ($data = $this->Cache_Get($sCacheTag))) {
      $data = array('collection'=>$this->oMapper->GetTopicsIdByRegexp($sRegexp, $iCount, $iPage, $iPerPage, $aParams),
          'count'=>$iCount);
      $this->Cache_Set($data, $sCacheTag, array('topic_update', 'topic_new'), 60*60);
    }
    return $data;
  }

  /**
   * Получает список комментариев по регулярному выражению (поиск)
   *
   * @param unknown_type $sRegexp
   * @param unknown_type $iPage
   * @param unknown_type $iPerPage
   * @return unknown
   */
  public function GetCommentsIdByRegexp($sRegexp, $iPage, $iPerPage, $aParams=array()) {
    $sTag = md5(serialize($sRegexp).serialize($aParams));
    $sCacheTag='mysearch_comments_'.$sTag.'_'.$iPage.'_'.$iPerPage;
    if (false === ($data = $this->Cache_Get($sCacheTag))) {
      $data = array('collection'=>$this->oMapper->GetCommentsIdByRegexp($sRegexp, $iCount, $iPage, $iPerPage, $aParams),
          'count'=>$iCount);
      $this->Cache_Set($data, $sCacheTag, array('topic_update', 'comment_new'), 60*60);
    }
    return $data;
  }

  /**
   * Получает список блогов по регулярному выражению (поиск)
   *
   * @param unknown_type $sRegexp
   * @param unknown_type $iPage
   * @param unknown_type $iPerPage
   * @return unknown
   */
  public function GetBlogsIdByRegexp($sRegexp, $iPage, $iPerPage, $aParams=array()) {
    $sTag = md5(serialize($sRegexp).serialize($aParams));
    $sCacheTag='mysearch_blogs_'.$sTag.'_'.$iPage.'_'.$iPerPage;
    if (false === ($data = $this->Cache_Get($sCacheTag))) {
      $data = array(
          'collection'=>$this->oMapper->GetBlogsIdByRegexp($sRegexp, $iCount, $iPage, $iPerPage, $aParams),
          'count'=>$iCount);
      $this->Cache_Set($data, $sCacheTag, array('blog_update', 'blog_new'), 60*60);
    }
    return $data;
  }

  public function GetBlogsByArrayId($aArrayId) {
    $sTag = md5(serialize($aArrayId));
    $sCacheTag='mysearch_blogs_list_'.$sTag;
    if (false === ($data = $this->Cache_Get($sCacheTag))) {
      $data = $this->oMapper->GetBlogsByArrayId($aArrayId);
      $this->Cache_Set($data, $sCacheTag, array('blog_update', 'blog_new'), 60*60);
    }
    return $data;
  }

}

// EOF