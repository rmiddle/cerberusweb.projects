<?php
abstract class Extension_ProjectsTab extends DevblocksExtension {
	const POINT = 'cerberusweb.projects.tab';
	
	function showTab() {}
	function saveTab() {}
};

class ProjectsTab extends Extension_ProjectsTab {
	function showTab() {
		$tpl = DevblocksPlatform::getTemplateService();
		
		// View
		$view_id = 'projects_list';
		
		$defaults = new C4_AbstractViewModel();
		$defaults->id = $view_id;
		$defaults->class_name = 'View_Project';
		
		$view = C4_AbstractViewLoader::getView($view_id, $defaults);
		$view->id = $view_id;
		$view->name = 'Projects';
		$tpl->assign('view', $view);
		
		C4_AbstractViewLoader::setView($view_id, $view);
		
		$tpl->display('devblocks:cerberusweb.projects::projects/projects/tab.tpl');
	}
	
	function saveTab() {
		
	}
};

class ProjectsQuestionsTab extends Extension_ProjectsTab {
	function showTab() {
		$tpl = DevblocksPlatform::getTemplateService();
		
		// View
		$view_id = 'projects_questions';
		
		$defaults = new C4_AbstractViewModel();
		$defaults->id = $view_id;
		$defaults->class_name = 'View_ProjectQuestion';
		
		$view = C4_AbstractViewLoader::getView($view_id, $defaults);
		$view->id = $view_id;
		$view->name = 'Questions';
		$view->addParamsDefault(array(
			SearchFields_ProjectQuestion::IS_ANSWERED => new DevblocksSearchCriteria(SearchFields_ProjectQuestion::IS_ANSWERED, '=', 0),
		), true);
		$tpl->assign('view', $view);
		
		C4_AbstractViewLoader::setView($view_id, $view);
		
		$tpl->display('devblocks:cerberusweb.projects::projects/questions/tab.tpl');
	}
	
	function saveTab() {
		
	}
};

class ProjectsProblemsTab extends Extension_ProjectsTab {
	function showTab() {
		$tpl = DevblocksPlatform::getTemplateService();
		
		// View
		$view_id = 'projects_problems';
		
		$defaults = new C4_AbstractViewModel();
		$defaults->id = $view_id;
		$defaults->class_name = 'View_ProjectProblem';
		
		$view = C4_AbstractViewLoader::getView($view_id, $defaults);
		$view->id = $view_id;
		$view->name = 'Problems';
		$view->addParamsDefault(array(
			SearchFields_ProjectProblem::IS_SOLVED => new DevblocksSearchCriteria(SearchFields_ProjectProblem::IS_SOLVED, '=', 0),
		),true);
		$tpl->assign('view', $view);
		
		C4_AbstractViewLoader::setView($view_id, $view);
		
		$tpl->display('devblocks:cerberusweb.projects::projects/problems/tab.tpl');
	}
	
	function saveTab() {
		
	}
};

class ProjectsIdeasTab extends Extension_ProjectsTab {
	function showTab() {
		$tpl = DevblocksPlatform::getTemplateService();
		$translate = DevblocksPlatform::getTranslationService();
		
		// View
		$view_id = 'projects_ideas';
		
		$defaults = new C4_AbstractViewModel();
		$defaults->id = $view_id;
		$defaults->class_name = 'View_ProjectIdea';
		
		$view = C4_AbstractViewLoader::getView($view_id, $defaults);
		$view->id = $view_id;
		$view->name = $translate->_('projects.common.ideas');
		$view->addParamsDefault(array(
			SearchFields_ProjectIdea::IS_CLOSED => new DevblocksSearchCriteria(SearchFields_ProjectIdea::IS_CLOSED, '=', 0),
		),true);
		$tpl->assign('view', $view);
		
		C4_AbstractViewLoader::setView($view_id, $view);
		
		$tpl->display('devblocks:cerberusweb.projects::projects/ideas/tab.tpl');
	}
	
	function saveTab() {
		
	}
};

class Page_Projects extends CerberusPageExtension {
	function isVisible() {
		$active_worker = CerberusApplication::getActiveWorker();
		return ($active_worker) ? true : false;
	}
	
	function render() {
		$tpl = DevblocksPlatform::getTemplateService();
		$visit = CerberusApplication::getVisit();

		$response = DevblocksPlatform::getHttpResponse();

		// Remember the last tab/URL
		if(null == ($selected_tab = @$response->path[1])) {
			$selected_tab = $visit->get('cerberusweb.projects.tab', '');
		}
		$tpl->assign('selected_tab', $selected_tab);

		// Path
		$stack = $response->path;
		@array_shift($stack); // projects
		@$module = array_shift($stack); // server
		
		switch($module) {
			case 'project':
				@$id = array_shift($stack); // id
				if(is_numeric($id) && null != ($project = DAO_Project::get($id)))
					$tpl->assign('project', $project);
				
				$tab_manifests = DevblocksPlatform::getExtensions('cerberusweb.projects.project.tab', false);
				uasort($tab_manifests, create_function('$a, $b', "return strcasecmp(\$a->name,\$b->name);\n"));
				$tpl->assign('tab_manifests', $tab_manifests);
				
				$tpl->display('devblocks:cerberusweb.projects::projects/projects/display/index.tpl');
				break;
				
			case 'question':
				@$id = array_shift($stack); // id
				if(is_numeric($id) && null != ($question = DAO_ProjectQuestion::get($id)))
					$tpl->assign('question', $question);
				
				$tab_manifests = DevblocksPlatform::getExtensions('cerberusweb.projects.project.tab', false);
				uasort($tab_manifests, create_function('$a, $b', "return strcasecmp(\$a->name,\$b->name);\n"));
				$tpl->assign('tab_manifests', $tab_manifests);
				
				$tpl->display('devblocks:cerberusweb.projects::projects/questions/display/index.tpl');
				break;
				
			case 'problem':
				@$id = array_shift($stack); // id
				if(is_numeric($id) && null != ($problem = DAO_ProjectProblem::get($id)))
					$tpl->assign('problem', $problem);
				
				$tab_manifests = DevblocksPlatform::getExtensions('cerberusweb.projects.project.tab', false);
				uasort($tab_manifests, create_function('$a, $b', "return strcasecmp(\$a->name,\$b->name);\n"));
				$tpl->assign('tab_manifests', $tab_manifests);
				
				$tpl->display('devblocks:cerberusweb.projects::projects/problems/display/index.tpl');
				break;
			
			case 'idea':
				@$id = array_shift($stack); // id
				if(is_numeric($id) && null != ($idea = DAO_ProjectIdea::get($id)))
					$tpl->assign('idea', $idea);
				
				$tab_manifests = DevblocksPlatform::getExtensions('cerberusweb.projects.project.tab', false);
				uasort($tab_manifests, create_function('$a, $b', "return strcasecmp(\$a->name,\$b->name);\n"));
				$tpl->assign('tab_manifests', $tab_manifests);
				
				$tpl->display('devblocks:cerberusweb.projects::projects/ideas/display/index.tpl');
				break;
				
			default:
				$tab_manifests = DevblocksPlatform::getExtensions('cerberusweb.projects.tab', false);
				uasort($tab_manifests, create_function('$a, $b', "return strcasecmp(\$a->name,\$b->name);\n"));
				$tpl->assign('tab_manifests', $tab_manifests);
				
				$tpl->display('devblocks:cerberusweb.projects::projects/index.tpl');
				break;
		}
		
	}
	
	// Ajax
	function showTabAction() {
		@$ext_id = DevblocksPlatform::importGPC($_REQUEST['ext_id'],'string','');
		
		$visit = CerberusApplication::getVisit();
		
		if(null != ($tab_mft = DevblocksPlatform::getExtension($ext_id)) 
			&& null != ($inst = $tab_mft->createInstance()) 
			&& $inst instanceof Extension_ProjectsTab) {
				$visit->set(Extension_ProjectsTab::POINT, $inst->manifest->params['uri']);
				$inst->showTab();
		}
	}
	
	function showProjectPeekAction() {
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$view_id = DevblocksPlatform::importGPC($_REQUEST['view_id'],'string','');
		
		$tpl = DevblocksPlatform::getTemplateService();
		$tpl->assign('view_id', $view_id);
		
		// Model
		$model = null;
		if(empty($id) || null == ($model = DAO_Project::get($id)))
			$model = new Model_Project();
		
		$tpl->assign('model', $model);
		
		// Custom fields
//		$custom_fields = DAO_CustomField::getByContext('cerberusweb.contexts.datacenter.server'); 
//		$tpl->assign('custom_fields', $custom_fields);

//		$custom_field_values = DAO_CustomFieldValue::getValuesByContextIds('cerberusweb.contexts.datacenter.server', $id);
//		if(isset($custom_field_values[$id]))
//			$tpl->assign('custom_field_values', $custom_field_values[$id]);
		
//		$types = Model_CustomField::getTypes();
//		$tpl->assign('types', $types);
		
		// Render
		$tpl->display('devblocks:cerberusweb.projects::projects/projects/peek.tpl');
	}
	
	function saveProjectPeekAction() {
		$active_worker = CerberusApplication::getActiveWorker();
		
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$name = DevblocksPlatform::importGPC($_REQUEST['name'],'string','');
		@$prefix = DevblocksPlatform::importGPC($_REQUEST['prefix'],'string','');
		@$do_delete = DevblocksPlatform::importGPC($_REQUEST['do_delete'],'integer',0);
		
		if($do_delete) { // delete
			DAO_Project::delete($id);
			
		} else { // create | update
			$fields = array(
				DAO_Project::NAME => $name,
				DAO_Project::PREFIX => strtoupper(substr(DevblocksPlatform::strAlphaNum($prefix),0,8)),
			);
			
			// Create/Update
			if(empty($id)) {
				$id = DAO_Project::create($fields);
				
			} else {
				DAO_Project::update($id, $fields);
			}
			
			// Custom field saves
//			@$field_ids = DevblocksPlatform::importGPC($_POST['field_ids'], 'array', array());
//			DAO_CustomFieldValue::handleFormPost('cerberusweb.contexts.datacenter.server', $id, $field_ids);
			
			// [TODO] Context links
		}
	}
	
	function showQuestionPeekAction() {
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$view_id = DevblocksPlatform::importGPC($_REQUEST['view_id'],'string','');
		
		$tpl = DevblocksPlatform::getTemplateService();
		$tpl->assign('view_id', $view_id);
		
		// Model
		$model = null;
		if(empty($id) || null == ($model = DAO_ProjectQuestion::get($id)))
			$model = new Model_ProjectQuestion();
		
		$tpl->assign('model', $model);
		
		// Projects
		$projects = DAO_Project::getAll();
		$tpl->assign('projects', $projects);
		
		// Custom fields
//		$custom_fields = DAO_CustomField::getByContext('cerberusweb.contexts.datacenter.server'); 
//		$tpl->assign('custom_fields', $custom_fields);

//		$custom_field_values = DAO_CustomFieldValue::getValuesByContextIds('cerberusweb.contexts.datacenter.server', $id);
//		if(isset($custom_field_values[$id]))
//			$tpl->assign('custom_field_values', $custom_field_values[$id]);
		
//		$types = Model_CustomField::getTypes();
//		$tpl->assign('types', $types);
		
		// Render
		$tpl->display('devblocks:cerberusweb.projects::projects/questions/peek.tpl');
	}
	
	function saveQuestionPeekAction() {
		$active_worker = CerberusApplication::getActiveWorker();
		
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$summary = DevblocksPlatform::importGPC($_REQUEST['summary'],'string','');
		@$project_id = DevblocksPlatform::importGPC($_REQUEST['project_id'],'integer',0);
		@$do_delete = DevblocksPlatform::importGPC($_REQUEST['do_delete'],'integer',0);
		
		if($do_delete) { // delete
			DAO_ProjectQuestion::delete($id);
			
		} else { // create | update
			$fields = array(
				DAO_ProjectQuestion::SUMMARY => $summary,
				DAO_ProjectQuestion::PROJECT_ID => $project_id,
				DAO_ProjectQuestion::UPDATED => time(),
			);
			
			// Create/Update
			if(empty($id)) {
				$id = DAO_ProjectQuestion::create($fields);
				$fields = array();
				
				if(null != ($project = DAO_Project::get($project_id)))
					$fields[DAO_ProjectQuestion::MASK] = sprintf("%s-Q%d", $project->prefix, $id);
					
				if(!empty($fields))
					DAO_ProjectQuestion::update($id, $fields);
				
			} else {
				DAO_ProjectQuestion::update($id, $fields);
			}
			
			// Custom field saves
//			@$field_ids = DevblocksPlatform::importGPC($_POST['field_ids'], 'array', array());
//			DAO_CustomFieldValue::handleFormPost('cerberusweb.contexts.datacenter.server', $id, $field_ids);
			
			// [TODO] Context links
		}
	}	
	
	function showProblemPeekAction() {
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$view_id = DevblocksPlatform::importGPC($_REQUEST['view_id'],'string','');
		
		$tpl = DevblocksPlatform::getTemplateService();
		$tpl->assign('view_id', $view_id);
		
		// Model
		$model = null;
		if(empty($id) || null == ($model = DAO_ProjectProblem::get($id)))
			$model = new Model_ProjectProblem();
		
		$tpl->assign('model', $model);
		
		// Projects
		$projects = DAO_Project::getAll();
		$tpl->assign('projects', $projects);
		
		// Custom fields
//		$custom_fields = DAO_CustomField::getByContext('cerberusweb.contexts.datacenter.server'); 
//		$tpl->assign('custom_fields', $custom_fields);

//		$custom_field_values = DAO_CustomFieldValue::getValuesByContextIds('cerberusweb.contexts.datacenter.server', $id);
//		if(isset($custom_field_values[$id]))
//			$tpl->assign('custom_field_values', $custom_field_values[$id]);
		
//		$types = Model_CustomField::getTypes();
//		$tpl->assign('types', $types);
		
		// Render
		$tpl->display('devblocks:cerberusweb.projects::projects/problems/peek.tpl');
	}
	
	function saveProblemPeekAction() {
		$active_worker = CerberusApplication::getActiveWorker();
		
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$summary = DevblocksPlatform::importGPC($_REQUEST['summary'],'string','');
		@$project_id = DevblocksPlatform::importGPC($_REQUEST['project_id'],'integer',0);
		@$do_delete = DevblocksPlatform::importGPC($_REQUEST['do_delete'],'integer',0);
		
		if($do_delete) { // delete
			DAO_ProjectProblem::delete($id);
			
		} else { // create | update
			$fields = array(
				DAO_ProjectProblem::SUMMARY => $summary,
				DAO_ProjectProblem::PROJECT_ID => $project_id,
				DAO_ProjectProblem::UPDATED => time(),
			);
			
			// Create/Update
			if(empty($id)) {
				$id = DAO_ProjectProblem::create($fields);
				$fields = array();
				
				if(null != ($project = DAO_Project::get($project_id)))
					$fields[DAO_ProjectProblem::MASK] = sprintf("%s-P%d", $project->prefix, $id);
					
				if(!empty($fields))
					DAO_ProjectProblem::update($id, $fields);
				
			} else {
				DAO_ProjectProblem::update($id, $fields);
			}
			
			// Custom field saves
//			@$field_ids = DevblocksPlatform::importGPC($_POST['field_ids'], 'array', array());
//			DAO_CustomFieldValue::handleFormPost('cerberusweb.contexts.datacenter.server', $id, $field_ids);
			
			// [TODO] Context links
		}
	}	
	
	function showSuggestionPeekAction() {
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$view_id = DevblocksPlatform::importGPC($_REQUEST['view_id'],'string','');
		
		$tpl = DevblocksPlatform::getTemplateService();
		$tpl->assign('view_id', $view_id);
		
		// Model
		$model = null;
		if(empty($id) || null == ($model = DAO_ProjectIdea::get($id)))
			$model = new Model_ProjectIdea();
		
		$tpl->assign('model', $model);
		
		// Projects
		$projects = DAO_Project::getAll();
		$tpl->assign('projects', $projects);
		
		// Custom fields
//		$custom_fields = DAO_CustomField::getByContext('cerberusweb.contexts.datacenter.server'); 
//		$tpl->assign('custom_fields', $custom_fields);

//		$custom_field_values = DAO_CustomFieldValue::getValuesByContextIds('cerberusweb.contexts.datacenter.server', $id);
//		if(isset($custom_field_values[$id]))
//			$tpl->assign('custom_field_values', $custom_field_values[$id]);
		
//		$types = Model_CustomField::getTypes();
//		$tpl->assign('types', $types);
		
		// Render
		$tpl->display('devblocks:cerberusweb.projects::projects/ideas/peek.tpl');
	}
	
	function saveSuggestionPeekAction() {
		$active_worker = CerberusApplication::getActiveWorker();
		
		@$id = DevblocksPlatform::importGPC($_REQUEST['id'],'integer',0);
		@$summary = DevblocksPlatform::importGPC($_REQUEST['summary'],'string','');
		@$project_id = DevblocksPlatform::importGPC($_REQUEST['project_id'],'integer',0);
		@$do_delete = DevblocksPlatform::importGPC($_REQUEST['do_delete'],'integer',0);
		
		if($do_delete) { // delete
			DAO_ProjectIdea::delete($id);
			
		} else { // create | update
			$fields = array(
				DAO_ProjectIdea::SUMMARY => $summary,
				DAO_ProjectIdea::PROJECT_ID => $project_id,
				DAO_ProjectIdea::UPDATED => time(),
			);
			
			// Create/Update
			if(empty($id)) {
				$id = DAO_ProjectIdea::create($fields);
				$fields = array();
				
				if(null != ($project = DAO_Project::get($project_id)))
					$fields[DAO_ProjectIdea::MASK] = sprintf("%s-S%d", $project->prefix, $id);
					
				if(!empty($fields))
					DAO_ProjectIdea::update($id, $fields);	
				
			} else {
				DAO_ProjectIdea::update($id, $fields);
			}
			
			// Custom field saves
//			@$field_ids = DevblocksPlatform::importGPC($_POST['field_ids'], 'array', array());
//			DAO_CustomFieldValue::handleFormPost('cerberusweb.contexts.datacenter.server', $id, $field_ids);
			
			// [TODO] Context links
		}
	}	
};