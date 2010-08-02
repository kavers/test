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
* @File Name: Mysearch.mapper.class.php
* @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*----------------------------------------------------------------------------
*/

class Mapper_Mysearch extends Mapper {

  private function PrepareRegExp($sRegExp) {
    $sRegExpPrim = str_replace('[[:>:]]|[[:<:]]', '[[:space:]]+', $sRegExp);
    $sRegExpPrim = str_replace('|[[:<:]]', '[[:alnum:]]+[[:space:]]+', $sRegExpPrim);
    $sRegExpPrim = str_replace('[[:>:]]|', '[[:space:]]+[[:alnum:]]+', $sRegExpPrim);
    $aRegExp=array($sRegExpPrim, $sRegExp);
    return $aRegExp;
  }

  /**
   * Поиск текста по топикам
   *
   * @param <type> $aRegExp
   * @param <type> $iCount
   * @param <type> $iCurrPage
   * @param <type> $iPerPage
   * @param <type> $aParams
   * @return <type>
   */
  private function _GetTopicsIdByRegexp($aRegExp, &$iCount, $iCurrPage, $iPerPage, $aParams) {
    $aResult=array();
    if (!$aParams['bSkipTags']) {
      $sql = "
	SELECT DISTINCT t.topic_id, 
          CASE WHEN (LOWER(t.topic_title) REGEXP ?) THEN 1 ELSE 0 END +
          CASE WHEN (LOWER(tc.topic_text_source) REGEXP ?) THEN 1 ELSE 0 END AS weight
	FROM ".DB_TABLE_TOPIC." AS t 
          LEFT JOIN ".DB_TABLE_TOPIC_CONTENT." AS tc ON tc.topic_id=t.topic_id
	WHERE (topic_publish=1) AND
          ((LOWER(t.topic_title) REGEXP ?)
           {OR (LOWER(t.topic_title) REGEXP ?)}
            OR
           (LOWER(tc.topic_text_source) REGEXP ?)
           {OR (LOWER(tc.topic_text_source) REGEXP ?)}
	)
	ORDER BY
          weight DESC,
          t.topic_id ASC
	LIMIT ?d, ?d
	";
      $aRows=$this->oDb->selectPage($iCount, $sql,
          $aRegExp[0],
          $aRegExp[0],
          $aRegExp[0], (isset($aRegExp[1])? $aRegExp[1] : DBSIMPLE_SKIP),
          $aRegExp[0], (isset($aRegExp[1])? $aRegExp[1] : DBSIMPLE_SKIP),
          ($iCurrPage-1)*$iPerPage, $iPerPage);
    }

    if ($aRows) {
      foreach ($aRows as $aRow) {
        $aResult[]=$aRow['topic_id'];
      }
    }
    return $aResult;
  } // private function _GetTopicsIdByRegexp() //

  /**
   * Поиск текста по комментариям
   *
   * @param <type> $aRegExp
   * @param <type> $iCount
   * @param <type> $iCurrPage
   * @param <type> $iPerPage
   * @param <type> $aParams
   * @return <type>
   */
  private function _GetCommentsIdByRegexp($aRegExp, &$iCount, $iCurrPage, $iPerPage, $aParams) {
    $aResult=array();
    if (!$aParams['bSkipTags']) {
      $sql = "
	SELECT DISTINCT c.comment_id, 
          CASE WHEN (LOWER(c.comment_text) REGEXP ?) THEN 1 ELSE 0 END weight
	FROM ".DB_TABLE_TOPIC_COMMENT." AS c
	WHERE (comment_delete=0) AND
            (
              (LOWER(c.comment_text) REGEXP ?)
              {OR (LOWER(c.comment_text) REGEXP ?)}
            )
	ORDER BY
            weight DESC,
            c.comment_id ASC
	LIMIT ?d, ?d
	";
      $aRows=$this->oDb->selectPage($iCount, $sql,
          $aRegExp[0],
          $aRegExp[0], (isset($aRegExp[1])? $aRegExp[1] : DBSIMPLE_SKIP),
          ($iCurrPage-1)*$iPerPage, $iPerPage);
    }

    if ($aRows) {
      foreach ($aRows as $aRow) {
        $aResult[]=$aRow['comment_id'];
      }
    }
    return $aResult;
  } // private function _GetCommentsIdByRegexp(...) //

  /**
   * Поиск текста по блогам
   *
   * @param <type> $aRegExp
   * @param <type> $iCount
   * @param <type> $iCurrPage
   * @param <type> $iPerPage
   * @param <type> $aParams
   * @return <type>
   */
  private function _GetBlogsIdByRegexp($aRegExp, &$iCount, $iCurrPage, $iPerPage, $aParams) {
    $aResult=array();
    if (!$aParams['bSkipTags']) {
      $sql = "
	SELECT DISTINCT b.blog_id,
          CASE WHEN (LOWER(b.blog_title) REGEXP ?) THEN 1 ELSE 0 END weight
	FROM ".DB_TABLE_BLOG." AS b
	WHERE (
              (LOWER(b.blog_title) REGEXP ?)
              {OR (LOWER(b.blog_description) REGEXP ?)}
              )
	ORDER BY
            weight DESC,
            b.blog_id ASC
	LIMIT ?d, ?d
	";
      $aRows=$this->oDb->selectPage($iCount, $sql,
          $aRegExp[0],
          $aRegExp[0], (isset($aRegExp[1])? $aRegExp[1] : DBSIMPLE_SKIP),
          ($iCurrPage-1)*$iPerPage, $iPerPage);
    }

    if ($aRows) {
      foreach ($aRows as $aRow) {
        $aResult[]=$aRow['blog_id'];
      }
    }
    return $aResult;
  } // public function _GetBlogsIdByRegexp(...) //

  public function GetTopicsIdByRegexp($sRegExp, &$iCount, $iCurrPage, $iPerPage, $aParams) {
    $aRegExp = $this->PrepareRegExp($sRegExp);
    $aResult = $this->_GetTopicsIdByRegexp($aRegExp, $iCount, $iCurrPage, $iPerPage, $aParams);
    return $aResult;
  }

  public function GetCommentsIdByRegexp($sRegExp, &$iCount, $iCurrPage, $iPerPage, $aParams) {
    $aRegExp = $this->PrepareRegExp($sRegExp);
    $aResult = $this->_GetCommentsIdByRegexp($aRegExp, $iCount, $iCurrPage, $iPerPage, $aParams);
    return $aResult;
  }

  public function GetBlogsIdByRegexp($sRegExp, &$iCount, $iCurrPage, $iPerPage, $aParams) {
    $aRegExp = $this->PrepareRegExp($sRegExp);
    $aResult = $this->_GetBlogsIdByRegexp($aRegExp, $iCount, $iCurrPage, $iPerPage, $aParams);
    return $aResult;
  }

  public function GetBlogsByArrayId($aArrayId) {
    if (!is_array($aArrayId) or count($aArrayId)==0) {
      return array();
    }
    $sql = "
      SELECT
	b.*
      FROM
        ".DB_TABLE_BLOG." as b
      WHERE
        b.blog_id IN (?a)
      ";
    $aBlogs=array();
    if ($aRows=$this->oDb->select($sql, $aArrayId)) {
      foreach ($aRows as $aBlog) {
        $aBlogs[]=new BlogEntity_Blog($aBlog);
      }
    }
    return $aBlogs;
  }
}

// EOF