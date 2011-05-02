<?php
class DAO_ProjectProblem extends DevblocksORMHelper {
	const ID = 'id';
	const MASK = 'mask';
	const SUMMARY = 'summary';
	const PROJECT_ID = 'project_id';
	const UPDATED = 'updated';
	const IS_SOLVED = 'is_solved';
	const NUM_COMMENTS = 'num_comments';
	const NUM_VOTES_UP = 'num_votes_up';
	const NUM_VOTES_DOWN = 'num_votes_down';

	static function create($fields) {
		$db = DevblocksPlatform::getDatabaseService();
		
		$sql = "INSERT INTO project_problem () VALUES ()";
		$db->Execute($sql);
		$id = $db->LastInsertId();
		
		self::update($id, $fields);
		
		return $id;
	}
	
	static function update($ids, $fields) {
		parent::_update($ids, 'project_problem', $fields);
	}
	
	static function updateWhere($fields, $where) {
		parent::_updateWhere('project_problem', $fields, $where);
	}
	
	/**
	 * @param string $where
	 * @param mixed $sortBy
	 * @param mixed $sortAsc
	 * @param integer $limit
	 * @return Model_ProjectProblem[]
	 */
	static function getWhere($where=null, $sortBy=null, $sortAsc=true, $limit=null) {
		$db = DevblocksPlatform::getDatabaseService();

		list($where_sql, $sort_sql, $limit_sql) = self::_getWhereSQL($where, $sortBy, $sortAsc, $limit);
		
		// SQL
		$sql = "SELECT id, mask, summary, project_id, updated, is_solved, num_comments, num_votes_up, num_votes_down ".
			"FROM project_problem ".
			$where_sql.
			$sort_sql.
			$limit_sql
		;
		$rs = $db->Execute($sql);
		
		return self::_getObjectsFromResult($rs);
	}

	/**
	 * @param integer $id
	 * @return Model_ProjectProblem	 */
	static function get($id) {
		$objects = self::getWhere(sprintf("%s = %d",
			self::ID,
			$id
		));
		
		if(isset($objects[$id]))
			return $objects[$id];
		
		return null;
	}
	
	/**
	 * @param resource $rs
	 * @return Model_ProjectProblem[]
	 */
	static private function _getObjectsFromResult($rs) {
		$objects = array();
		
		while($row = mysql_fetch_assoc($rs)) {
			$object = new Model_ProjectProblem();
			$object->id = $row['id'];
			$object->mask = $row['mask'];
			$object->summary = $row['summary'];
			$object->project_id = $row['project_id'];
			$object->updated = $row['updated'];
			$object->is_solved = $row['is_solved'];
			$object->num_comments = $row['num_comments'];
			$object->num_votes_up = $row['num_votes_up'];
			$object->num_votes_down = $row['num_votes_down'];
			$objects[$object->id] = $object;
		}
		
		mysql_free_result($rs);
		
		return $objects;
	}
	
	static function delete($ids) {
		if(!is_array($ids)) $ids = array($ids);
		$db = DevblocksPlatform::getDatabaseService();
		
		if(empty($ids))
			return;
		
		$ids_list = implode(',', $ids);
		
		$db->Execute(sprintf("DELETE FROM project_problem WHERE id IN (%s)", $ids_list));
		
		return true;
	}
	
	public static function getSearchQueryComponents($columns, $params, $sortBy=null, $sortAsc=null) {
		$fields = SearchFields_ProjectProblem::getFields();
		
		// Sanitize
		if(!isset($fields[$sortBy]))
			$sortBy=null;

        list($tables,$wheres) = parent::_parseSearchParams($params, $columns, $fields, $sortBy);
		
		$select_sql = sprintf("SELECT ".
			"project_problem.id as %s, ".
			"project_problem.mask as %s, ".
			"project_problem.summary as %s, ".
			"project_problem.project_id as %s, ".
			"project_problem.updated as %s, ".
			"project_problem.is_solved as %s, ".
			"project_problem.num_comments as %s, ".
			"project_problem.num_votes_up as %s, ".
			"project_problem.num_votes_down as %s ",
				SearchFields_ProjectProblem::ID,
				SearchFields_ProjectProblem::MASK,
				SearchFields_ProjectProblem::SUMMARY,
				SearchFields_ProjectProblem::PROJECT_ID,
				SearchFields_ProjectProblem::UPDATED,
				SearchFields_ProjectProblem::IS_SOLVED,
				SearchFields_ProjectProblem::NUM_COMMENTS,
				SearchFields_ProjectProblem::NUM_VOTES_UP,
				SearchFields_ProjectProblem::NUM_VOTES_DOWN
			);
			
		$join_sql = "FROM project_problem ";
		
		// Custom field joins
		//list($select_sql, $join_sql, $has_multiple_values) = self::_appendSelectJoinSqlForCustomFieldTables(
		//	$tables,
		//	$params,
		//	'project_problem.id',
		//	$select_sql,
		//	$join_sql
		//);

		$where_sql = "".
			(!empty($wheres) ? sprintf("WHERE %s ",implode(' AND ',$wheres)) : "WHERE 1 ");
			
		$sort_sql = (!empty($sortBy)) ? sprintf("ORDER BY %s %s ",$sortBy,($sortAsc || is_null($sortAsc))?"ASC":"DESC") : " ";
	
		return array(
			'primary_table' => 'project_problem',
			'select' => $select_sql,
			'join' => $join_sql,
			'where' => $where_sql,
			'has_multiple_values' => false,
			'sort' => $sort_sql,
		);
	}
	
    /**
     * Enter description here...
     *
     * @param array $columns
     * @param DevblocksSearchCriteria[] $params
     * @param integer $limit
     * @param integer $page
     * @param string $sortBy
     * @param boolean $sortAsc
     * @param boolean $withCounts
     * @return array
     */
    static function search($columns, $params, $limit=10, $page=0, $sortBy=null, $sortAsc=null, $withCounts=true) {
		$db = DevblocksPlatform::getDatabaseService();
		
		// Build search queries
		$query_parts = self::getSearchQueryComponents($columns,$params,$sortBy,$sortAsc);

		$select_sql = $query_parts['select'];
		$join_sql = $query_parts['join'];
		$where_sql = $query_parts['where'];
		$has_multiple_values = $query_parts['has_multiple_values'];
		$sort_sql = $query_parts['sort'];
		
		$sql = 
			$select_sql.
			$join_sql.
			$where_sql.
			($has_multiple_values ? 'GROUP BY project_problem.id ' : '').
			$sort_sql;
			
		if($limit > 0) {
    		$rs = $db->SelectLimit($sql,$limit,$page*$limit) or die(__CLASS__ . '('.__LINE__.')'. ':' . $db->ErrorMsg()); /* @var $rs ADORecordSet */
		} else {
		    $rs = $db->Execute($sql) or die(__CLASS__ . '('.__LINE__.')'. ':' . $db->ErrorMsg()); /* @var $rs ADORecordSet */
            $total = mysql_num_rows($rs);
		}
		
		$results = array();
		$total = -1;
		
		while($row = mysql_fetch_assoc($rs)) {
			$result = array();
			foreach($row as $f => $v) {
				$result[$f] = $v;
			}
			$object_id = intval($row[SearchFields_ProjectProblem::ID]);
			$results[$object_id] = $result;
		}

		// [JAS]: Count all
		if($withCounts) {
			$count_sql = 
				($has_multiple_values ? "SELECT COUNT(DISTINCT project_problem.id) " : "SELECT COUNT(project_problem.id) ").
				$join_sql.
				$where_sql;
			$total = $db->GetOne($count_sql);
		}
		
		mysql_free_result($rs);
		
		return array($results,$total);
	}

};

class SearchFields_ProjectProblem implements IDevblocksSearchFields {
	const ID = 'p_id';
	const MASK = 'p_mask';
	const SUMMARY = 'p_summary';
	const PROJECT_ID = 'p_project_id';
	const UPDATED = 'p_updated';
	const IS_SOLVED = 'p_is_solved';
	const NUM_COMMENTS = 'p_num_comments';
	const NUM_VOTES_UP = 'p_num_votes_up';
	const NUM_VOTES_DOWN = 'p_num_votes_down';
	
	/**
	 * @return DevblocksSearchField[]
	 */
	static function getFields() {
		$translate = DevblocksPlatform::getTranslationService();
		
		$columns = array(
			self::ID => new DevblocksSearchField(self::ID, 'project_problem', 'id', $translate->_('common.id')),
			self::MASK => new DevblocksSearchField(self::MASK, 'project_problem', 'mask', $translate->_('projects.common.mask')),
			self::SUMMARY => new DevblocksSearchField(self::SUMMARY, 'project_problem', 'summary', $translate->_('common.summary')),
			self::PROJECT_ID => new DevblocksSearchField(self::PROJECT_ID, 'project_problem', 'project_id', $translate->_('projects.common.project')),
			self::UPDATED => new DevblocksSearchField(self::UPDATED, 'project_problem', 'updated', $translate->_('common.updated')),
			self::IS_SOLVED => new DevblocksSearchField(self::IS_SOLVED, 'project_problem', 'is_solved', $translate->_('dao.project_problem.is_solved')),
			self::NUM_COMMENTS => new DevblocksSearchField(self::NUM_COMMENTS, 'project_problem', 'num_comments', $translate->_('projects.common.dao.num_comments')),
			self::NUM_VOTES_UP => new DevblocksSearchField(self::NUM_VOTES_UP, 'project_problem', 'num_votes_up', $translate->_('projects.common.dao.num_votes_up')),
			self::NUM_VOTES_DOWN => new DevblocksSearchField(self::NUM_VOTES_DOWN, 'project_problem', 'num_votes_down', $translate->_('projects.common.dao.num_votes_down')),
		);
		
		// Custom Fields
		//$fields = DAO_CustomField::getByContext(CerberusContexts::XXX);

		//if(is_array($fields))
		//foreach($fields as $field_id => $field) {
		//	$key = 'cf_'.$field_id;
		//	$columns[$key] = new DevblocksSearchField($key,$key,'field_value',$field->name);
		//}
		
		// Sort by label (translation-conscious)
		uasort($columns, create_function('$a, $b', "return strcasecmp(\$a->db_label,\$b->db_label);\n"));

		return $columns;		
	}
};

class Model_ProjectProblem {
	public $id;
	public $mask;
	public $summary;
	public $project_id;
	public $updated;
	public $is_solved;
	public $num_comments;
	public $num_votes_up;
	public $num_votes_down;
};

class View_ProjectProblem extends C4_AbstractView {
	const DEFAULT_ID = 'projectproblem';

	function __construct() {
		$translate = DevblocksPlatform::getTranslationService();
	
		$this->id = self::DEFAULT_ID;
		$this->name = $translate->_('Problems');
		$this->renderLimit = 25;
		$this->renderSortBy = SearchFields_ProjectProblem::ID;
		$this->renderSortAsc = true;

		$this->view_columns = array(
			SearchFields_ProjectProblem::MASK,
			SearchFields_ProjectProblem::PROJECT_ID,
			SearchFields_ProjectProblem::UPDATED,
			SearchFields_ProjectProblem::NUM_COMMENTS,
			SearchFields_ProjectProblem::NUM_VOTES_UP,
			SearchFields_ProjectProblem::NUM_VOTES_DOWN,
		);
		$this->addColumnsHidden(array(
		));
		
		$this->addParamsHidden(array(
		));
		
		$this->doResetCriteria();
	}

	function getData() {
		$objects = DAO_ProjectProblem::search(
			$this->view_columns,
			$this->getParams(),
			$this->renderLimit,
			$this->renderPage,
			$this->renderSortBy,
			$this->renderSortAsc,
			$this->renderTotal
		);
		return $objects;
	}
	
	function getDataSample($size) {
		return $this->_doGetDataSample('DAO_ProjectProblem', $size);
	}

	function render() {
		$this->_sanitize();
		
		$tpl = DevblocksPlatform::getTemplateService();
		$tpl->assign('id', $this->id);
		$tpl->assign('view', $this);

		// Projects
		$projects = DAO_Project::getAll();
		$tpl->assign('projects', $projects);
		
		// Custom fields
		//$custom_fields = DAO_CustomField::getByContext(CerberusContexts::XXX);
		//$tpl->assign('custom_fields', $custom_fields);

		$tpl->display('devblocks:cerberusweb.projects::projects/problems/view.tpl');
	}

	function renderCriteria($field) {
		$tpl = DevblocksPlatform::getTemplateService();
		$tpl->assign('id', $this->id);

		switch($field) {
			case SearchFields_ProjectProblem::SUMMARY:
			case SearchFields_ProjectProblem::MASK:
				$tpl->display('devblocks:cerberusweb.core::internal/views/criteria/__string.tpl');
				break;
			case SearchFields_ProjectProblem::NUM_COMMENTS:
			case SearchFields_ProjectProblem::NUM_VOTES_UP:
			case SearchFields_ProjectProblem::NUM_VOTES_DOWN:
			case SearchFields_ProjectProblem::ID:
			case SearchFields_ProjectProblem::PROJECT_ID:
				$tpl->display('devblocks:cerberusweb.core::internal/views/criteria/__number.tpl');
				break;
			case SearchFields_ProjectProblem::IS_SOLVED:
				$tpl->display('devblocks:cerberusweb.core::internal/views/criteria/__bool.tpl');
				break;
			case SearchFields_ProjectProblem::UPDATED:
				$tpl->display('devblocks:cerberusweb.core::internal/views/criteria/__date.tpl');
				break;
			/*
			default:
				// Custom Fields
				if('cf_' == substr($field,0,3)) {
					$this->_renderCriteriaCustomField($tpl, substr($field,3));
				} else {
					echo ' ';
				}
				break;
			*/
		}
	}

	function renderCriteriaParam($param) {
		$field = $param->field;
		$values = !is_array($param->value) ? array($param->value) : $param->value;

		switch($field) {
			default:
				parent::renderCriteriaParam($param);
				break;
		}
	}

	function getFields() {
		return SearchFields_ProjectProblem::getFields();
	}

	function doSetCriteria($field, $oper, $value) {
		$criteria = null;

		switch($field) {
			case SearchFields_ProjectProblem::MASK:
			case SearchFields_ProjectProblem::SUMMARY:
				// force wildcards if none used on a LIKE
				if(($oper == DevblocksSearchCriteria::OPER_LIKE || $oper == DevblocksSearchCriteria::OPER_NOT_LIKE)
				&& false === (strpos($value,'*'))) {
					$value = $value.'*';
				}
				$criteria = new DevblocksSearchCriteria($field, $oper, $value);
				break;
			case SearchFields_ProjectProblem::ID:
			case SearchFields_ProjectProblem::PROJECT_ID:
			case SearchFields_ProjectProblem::NUM_COMMENTS:
			case SearchFields_ProjectProblem::NUM_VOTES_UP:
			case SearchFields_ProjectProblem::NUM_VOTES_DOWN:
				$criteria = new DevblocksSearchCriteria($field,$oper,$value);
				break;
				
			case SearchFields_ProjectProblem::UPDATED:
				@$from = DevblocksPlatform::importGPC($_REQUEST['from'],'string','');
				@$to = DevblocksPlatform::importGPC($_REQUEST['to'],'string','');

				if(empty($from)) $from = 0;
				if(empty($to)) $to = 'today';

				$criteria = new DevblocksSearchCriteria($field,$oper,array($from,$to));
				break;
				
			case SearchFields_ProjectProblem::IS_SOLVED:
				@$bool = DevblocksPlatform::importGPC($_REQUEST['bool'],'integer',1);
				$criteria = new DevblocksSearchCriteria($field,$oper,$bool);
				break;
				
			/*
			default:
				// Custom Fields
				if(substr($field,0,3)=='cf_') {
					$criteria = $this->_doSetCriteriaCustomField($field, substr($field,3));
				}
				break;
			*/
		}

		if(!empty($criteria)) {
			$this->addParam($criteria, $field);
			$this->renderPage = 0;
		}
	}
		
	function doBulkUpdate($filter, $do, $ids=array()) {
		@set_time_limit(0);
	  
		$change_fields = array();
		$custom_fields = array();

		// Make sure we have actions
		if(empty($do))
			return;

		// Make sure we have checked items if we want a checked list
		if(0 == strcasecmp($filter,"checks") && empty($ids))
			return;
			
		if(is_array($do))
		foreach($do as $k => $v) {
			switch($k) {
				// [TODO] Implement actions
				case 'example':
					//$change_fields[DAO_ProjectProblem::EXAMPLE] = 'some value';
					break;
				/*
				default:
					// Custom fields
					if(substr($k,0,3)=="cf_") {
						$custom_fields[substr($k,3)] = $v;
					}
					break;
				*/
			}
		}

		$pg = 0;

		if(empty($ids))
		do {
			list($objects,$null) = DAO_ProjectProblem::search(
				array(),
				$this->getParams(),
				100,
				$pg++,
				SearchFields_ProjectProblem::ID,
				true,
				false
			);
			$ids = array_merge($ids, array_keys($objects));
			 
		} while(!empty($objects));

		$batch_total = count($ids);
		for($x=0;$x<=$batch_total;$x+=100) {
			$batch_ids = array_slice($ids,$x,100);
			
			DAO_ProjectProblem::update($batch_ids, $change_fields);

			// [TODO] Custom Fields
			//self::_doBulkSetCustomFields('...', $custom_fields, $batch_ids);
			
			unset($batch_ids);
		}

		unset($ids);
	}			
};

class Context_ProjectProblem extends Extension_DevblocksContext {
    function getPermalink($context_id) {
    	$url_writer = DevblocksPlatform::getUrlService();
    	return $url_writer->write('c=projects&tab=problem&id='.$context_id, true);
    }
    
	function getContext($problem, &$token_labels, &$token_values, $prefix=null) {
		if(is_null($prefix))
			$prefix = 'Problem:';
		
		$translate = DevblocksPlatform::getTranslationService();
		//$fields = DAO_CustomField::getByContext(CerberusContexts::CONTEXT_OPPORTUNITY);

		// Polymorph
		if(is_numeric($problem)) {
			$problem = DAO_ProjectProblem::get($problem);
		} elseif($problem instanceof Model_ProjectProblem) {
			// It's what we want already.
		} else {
			$problem = null;
		}
		
		// Token labels
		$token_labels = array(
			'summary' => $prefix.$translate->_('common.summary'),
		);
		
//		if(is_array($fields))
//		foreach($fields as $cf_id => $field) {
//			$token_labels['custom_'.$cf_id] = $prefix.$field->name;
//		}

		// Token values
		$token_values = array();
		
		// Token values
		if($problem) {
			$token_values['id'] = $problem->id;
			$token_values['summary'] = $problem->summary;
//			if(!empty($org->city))
//				$token_values['city'] = $org->city;

//			$token_values['custom'] = array();
			
//			$field_values = array_shift(DAO_CustomFieldValue::getValuesByContextIds(CerberusContexts::CONTEXT_OPPORTUNITY, $opp->id));
//			if(is_array($field_values) && !empty($field_values)) {
//				foreach($field_values as $cf_id => $cf_val) {
//					if(!isset($fields[$cf_id]))
//						continue;
//					
//					// The literal value
//					if(null != $opp)
//						$token_values['custom'][$cf_id] = $cf_val;
//					
//					// Stringify
//					if(is_array($cf_val))
//						$cf_val = implode(', ', $cf_val);
//						
//					if(is_string($cf_val)) {
//						if(null != $opp)
//							$token_values['custom_'.$cf_id] = $cf_val;
//					}
//				}
//			}
		}
		
		// Assignee
//		@$assignee_id = $opp->worker_id;
//		$merge_token_labels = array();
//		$merge_token_values = array();
//		CerberusContexts::getContext(CerberusContexts::CONTEXT_WORKER, $assignee_id, $merge_token_labels, $merge_token_values, '', true);
//
//		CerberusContexts::merge(
//			'assignee_',
//			'Assignee:',
//			$merge_token_labels,
//			$merge_token_values,
//			$token_labels,
//			$token_values
//		);		
		
		return true;
	}

	function getChooserView() {
		$active_worker = CerberusApplication::getActiveWorker();
		
		// View
		$view_id = 'chooser_'.str_replace('.','_',$this->id).time().mt_rand(0,9999);
		$defaults = new C4_AbstractViewModel();
		$defaults->id = $view_id;
		$defaults->is_ephemeral = true;
		$defaults->class_name = $this->getViewClass();
		$view = C4_AbstractViewLoader::getView($view_id, $defaults);
		$view->name = 'Problems';
//		$view->view_columns = array(
//			SearchFields_ProjectProblem::NAME,
//			SearchFields_Project::PREFIX,
//		);
		$view->addParams(array(
			//SearchFields_CrmOpportunity::WORKER_ID => new DevblocksSearchCriteria(SearchFields_CrmOpportunity::WORKER_ID,'=',$active_worker->id),
		), true);
		$view->renderSortBy = SearchFields_ProjectProblem::UPDATED;
		$view->renderSortAsc = false;
		$view->renderLimit = 10;
		$view->renderTemplate = 'contextlinks_chooser';
		
		C4_AbstractViewLoader::setView($view_id, $view);
		return $view;
	}
	
	function getView($context=null, $context_id=null, $options=array()) {
		$view_id = str_replace('.','_',$this->id);
		
		$defaults = new C4_AbstractViewModel();
		$defaults->id = $view_id; 
		$defaults->class_name = $this->getViewClass();
		$view = C4_AbstractViewLoader::getView($view_id, $defaults);
		$view->name = 'Problems';
		
		$params = array();
		
		if(!empty($context) && !empty($context_id)) {
			$params = array(
				new DevblocksSearchCriteria(SearchFields_ProjectProblem::CONTEXT_LINK,'=',$context),
				new DevblocksSearchCriteria(SearchFields_ProjectProblem::CONTEXT_LINK_ID,'=',$context_id),
			);
		}
		
		if(isset($options['filter_open']))
			$params[] = new DevblocksSearchCriteria(SearchFields_ProjectProblem::IS_SOLVED,'=',0);
		
		$view->addParams($params, true);
		
		$view->renderTemplate = 'context';
		C4_AbstractViewLoader::setView($view_id, $view);
		return $view;
	}
};