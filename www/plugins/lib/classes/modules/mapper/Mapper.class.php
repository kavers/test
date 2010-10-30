<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright � 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

class PluginLib_ModuleMapper extends Module {
	public function Init() {
	}
	
	/**
	* ����������� �������� ���������� � where statment.
	* 
	* @param	array		��������� ���������� 
	* 						array(
	* 							'eq' => array('fieldName' => val) //�������� ���� �����
	* 							'more' => array('fieldName' => val) //�������� ���� ������ ���
	* 							'less' => array('fieldName' => val) //�������� ���� ������ ���
	*							'in' => array('fieldName' => array(val)) //�������� ���� ����������� ���������
	*						)
	* @param	string		��� ������� (��� ���������) ��� �������� ����� ������� �����
	* @return	string		where statment
	*/
	static public function BuildFilter($aFilter, $sTableAlias) {
		if(!is_array($aFilter)) {
			return '';
		}
		
		$sWhere = '';
		
		foreach($aFilter as $sFilterType => $aFilterValue) {
			switch(strtoupper($sFilterType)) {
				case 'EQ':
					$sWhere .= self::buildRelationFilter($aFilterValue, $sTableAlias, '=');
					break;
				case 'MORE':
					$sWhere .= self::buildRelationFilter($aFilterValue, $sTableAlias, '>=');
					break;
				case 'LESS':
					$sWhere .= self::buildRelationFilter($aFilterValue, $sTableAlias, '<=');
					break;
				case 'IN':
					$sWhere .= self::buildInFilter($aFilterValue, $sTableAlias);
					break;
				case 'BEGINLIKE':
					$sWhere .= self::buildLikeFilter($aFilterValue, $sTableAlias, 'begin');
					break;
				case 'ENDLIKE':
					$sWhere .= self::buildLikeFilter($aFilterValue, $sTableAlias, 'end');
					break;
				case 'MIDDLELIKE':
					$sWhere .= self::buildLikeFilter($aFilterValue, $sTableAlias, 'middle');
			}
		}

		return $sWhere;
	}
	
	/**
	* ����������� ������ ��� �������� ������� � limit statment
	* 
	* @param	array		array('iPage', 'iElementsPerPage') //�������� ���������� � 1
	* @return	string		LIMIT ... | ''
	*/
	static public function BuildLimit($aPaging) {
		if(!is_array($aPaging)) {
			return '';
		}
		
		if(!isset($aPaging['iPage']) || !isset($aPaging['iElementsPerPage'])) {
			return '';
		}
		
		$iPage = abs((int)($aPaging['iPage']));
		$iElementsPerPage = abs((int)($aPaging['iElementsPerPage']));
		
		return 'LIMIT ' . ($iPage - 1) * $iElementsPerPage . ',' . $iElementsPerPage;
	}
	
	/**
	* ����������� ������ ���������� � ORDER statment
	* 
	* @param	array		(��� ���� => �����������)
	* @param	string		��� ������� (��� ���������) ��� �������� ����� ������� �����
	* @return	string		ORDER statment
	*/
	static public function BuildOrder($aOrder, $sTableAlias) {
		if(!is_array($aOrder)) {
			return '';
		}
		
		$sResult = '';
		
		foreach($aOrder as $sFieldName => $sDirection) {
			$sFullFieldName = $sTableAlias . '.' . $sFieldName;
			$sCheckedDirection = '';
			if($sDirection) {
				switch(strtoupper($sDirection)) {
					case 'ASC':
						$sCheckedDirection = ' ASC';
						break;
					case 'DESC':
						$sCheckedDirection = ' DESC';
				}
			}
			
			$sResult = $sResult ? $sResult . ', ' . $sFullFieldName . $sCheckedDirection : 'ORDER BY ' . $sFullFieldName . $sCheckedDirection;
		}
		
		return $sResult;
	}
	
	static protected function arrayPrepareForINStatment($aValue) {
		if(!is_array($aValue)) {
			return '';
		}
		
		$aValueResult = array();
		foreach($aValue as $val) {
			$aValueResult[] = '"' . mysql_real_escape_string($val) . '"';
		}
		
		return implode(',', $aValueResult);
	}
	
	static protected function buildRelationFilter($aConfig, $sTableAlias, $sRelation) {
		if(!is_array($aConfig)) {
			return '';
		}
		
		$sWhere = '';
		
		foreach($aConfig as $sFieldName => $sValue) {
			$sFullFieldName = $sTableAlias . '.' . $sFieldName;
			$sEscapedValue = mysql_real_escape_string($sValue);
			$sWhere.=" AND {$sFullFieldName} {$sRelation} \"{$sEscapedValue}\"";
		}
		
		return $sWhere;
	}
	
	static protected function buildInFilter($aConfig, $sTableAlias) {
		if(!is_array($aConfig)) {
			return '';
		}
		
		$sWhere = '';
		
		foreach($aConfig as $sFieldName => $aValue) {
			$sFullFieldName = $sTableAlias . '.' . $sFieldName;
			$sIn = self::arrayPrepareForINStatment($aValue);
			$sWhere.=" AND {$sFullFieldName} IN ({$sIn})";
		}
		
		return $sWhere;
	}
	
	static protected function buildLikeFilter($aConfig, $sTableAlias, $sType) {
		if(!is_array($aConfig)) {
			return '';
		}
		
		switch(strtoupper($sType)) {
			case 'BEGIN':
				$sFormatString = ' AND %s LIKE "%s%%"';
				break;
			case 'END':
				$sFormatString = ' AND %s LIKE "%%%s"';
				break;
			case 'MIDDLE':
			default:
				$sFormatString = ' AND %s LIKE "%%%s%%"';
				break;
		}
		$sWhere = '';
		
		foreach($aConfig as $sFieldName => $sValue) {
			$sFullFieldName = $sTableAlias . '.' . $sFieldName;
			$sEscapedValue = mysql_real_escape_string($sValue);
			$sWhere .= sprintf($sFormatString, $sFullFieldName, $sEscapedValue);
		}
		
		return $sWhere;
	}
}
?>