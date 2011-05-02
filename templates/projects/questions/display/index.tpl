<ul class="submenu">
	<li><a href="{devblocks_url}c=projects&a=questions{/devblocks_url}">{'projects.common.questions'|devblocks_translate|lower}</a></li>
</ul>
<div style="clear:both;"></div>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td valign="top" style="padding-right:5px;">
		<fieldset>
			<legend>{'projects.common.question'|devblocks_translate}: {$question->mask}</legend>
			<h1><b>{$question->summary}</b></h1> 
	
			<form action="{devblocks_url}{/devblocks_url}" onsubmit="return false;">
			{*
			<b>{'task.is_completed'|devblocks_translate|capitalize}:</b> {if $task->is_completed}{'common.yes'|devblocks_translate|capitalize}{else}{'common.no'|devblocks_translate|capitalize}{/if} &nbsp;
			{if !empty($task->updated_date)}
			<b>{'task.updated_date'|devblocks_translate|capitalize}:</b> <abbr title="{$task->updated_date|devblocks_date}">{$task->updated_date|devblocks_prettytime}</abbr> &nbsp;
			{/if}
			{if !empty($task->due_date)}
			<b>{'task.due_date'|devblocks_translate|capitalize}:</b> <abbr title="{$task->due_date|devblocks_date}">{$task->due_date|devblocks_prettytime}</abbr> &nbsp;
			{/if}
			{assign var=task_worker_id value=$task->worker_id}
			{if !empty($task_worker_id) && isset($workers.$task_worker_id)}
				<b>{'common.worker'|devblocks_translate|capitalize}:</b> {$workers.$task_worker_id->getName()} &nbsp;
			{/if}
			<br>
			*}
			
			<!-- Toolbar -->
			<button type="button" id="btnProjectQuestionEdit"><span class="cerb-sprite sprite-document_edit"></span> Edit</button>
			{*
			{$toolbar_extensions = DevblocksPlatform::getExtensions('cerberusweb.task.toolbaritem',true)}
			{foreach from=$toolbar_extensions item=toolbar_extension}
				{$toolbar_extension->render($task)}
			{/foreach}
			*}
			</form>
		</fieldset>
	</td>
	<td align="right" valign="top">
		{*
		<form action="{devblocks_url}{/devblocks_url}" method="post">
		<input type="hidden" name="c" value="contacts">
		<input type="hidden" name="a" value="doOrgQuickSearch">
		<span><b>{$translate->_('common.quick_search')|capitalize}:</b></span> <select name="type">
			<option value="name">{$translate->_('contact_org.name')|capitalize}</option>
			<option value="phone">{$translate->_('contact_org.phone')|capitalize}</option>
		</select><input type="text" name="query" class="input_search" size="24"><button type="submit">{$translate->_('common.search_go')|lower}</button>
		</form>
		*}
	</td>
</tr>
</table>

<div id="projectQuestionTabs">
	<ul>
		<li><a href="{devblocks_url}ajax.php?c=internal&a=showTabContextComments&context=cerberusweb.contexts.projects.question&id={$question->id}{/devblocks_url}">{'common.comments'|devblocks_translate|capitalize}</a></li>
		<li><a href="{devblocks_url}ajax.php?c=internal&a=showTabContextLinks&context=cerberusweb.contexts.projects.question&id={$question->id}{/devblocks_url}">{'common.links'|devblocks_translate}</a></li>

		{$tabs = [comments, links]}
		
		{foreach from=$tab_manifests item=tab_manifest}
			{$tabs[] = $tab_manifest->params.uri}
			<li><a href="{devblocks_url}ajax.php?c=projects&a=showProjectTab&ext_id={$tab_manifest->id}&project_id={$question->id}{/devblocks_url}"><i>{$tab_manifest->params.title|devblocks_translate}</i></a></li>
		{/foreach}
	</ul>
</div> 
<br>

{$tab_selected_idx=0}
{foreach from=$tabs item=tab_label name=tabs}
	{if $tab_label==$tab_selected}{$tab_selected_idx = $smarty.foreach.tabs.index}{/if}
{/foreach}

<script type="text/javascript">
	$(function() {
		var tabs = $("#projectQuestionTabs").tabs( { selected:{$tab_selected_idx} } );
		
		$('#btnProjectQuestionEdit').bind('click', function() {
			$popup = genericAjaxPopup('peek','c=projects&a=showQuestionPeek&id={$question->id}',null,false,'550');
			$popup.one('projects_question_save', function(event) {
				event.stopPropagation();
				document.location.href = '{devblocks_url}c=projects&a=question&id={$question->id}{/devblocks_url}';
			});
		})
	});
</script>
