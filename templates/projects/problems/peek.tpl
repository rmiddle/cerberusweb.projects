<form action="{devblocks_url}{/devblocks_url}" method="post" id="frmProjectProblem">
<input type="hidden" name="c" value="projects">
<input type="hidden" name="a" value="saveProblemPeek">
{if !empty($model) && !empty($model->id)}<input type="hidden" name="id" value="{$model->id}">{/if}
<input type="hidden" name="do_delete" value="0">

<fieldset>
	<legend>{'common.properties'|devblocks_translate}</legend>
	
	<table cellspacing="0" cellpadding="2" border="0" width="98%">
		<tr>
			<td width="1%" nowrap="nowrap" valign="top"><b>{'common.summary'|devblocks_translate}:</b></td>
			<td width="99%">
				<input type="text" name="summary" value="{$model->summary}" style="width:98%;">
			</td>
		</tr>
		<tr>
			<td width="1%" nowrap="nowrap" valign="top"><b>{'projects.common.project'|devblocks_translate}:</b></td>
			<td width="99%">
				<select name="project_id">
					{foreach from=$projects item=project key=project_id}
					<option value="{$project_id}" {if $model->project_id==$project_id}selected="selected"{/if}>{$project->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
</fieldset>

{if !empty($custom_fields)}
<fieldset>
	<legend>{'common.custom_fields'|devblocks_translate}</legend>
	{include file="devblocks:cerberusweb.core::internal/custom_fields/bulk/form.tpl" bulk=false}
</fieldset>
{/if}

{* [TODO] Don't delete projects unless they're empty *}

<button type="button" onclick="genericAjaxPopupPostCloseReloadView('peek','frmProjectProblem','{$view_id}', false, 'projects_problem_save');"><span class="cerb-sprite sprite-check"></span> {$translate->_('common.save_changes')|capitalize}</button>
{if $model->id && $active_worker->is_superuser}<button type="button" onclick="if(confirm('Permanently delete this problem?')) { this.form.do_delete.value='1';genericAjaxPopupPostCloseReloadView('peek','frmProjectProblem','{$view_id}'); } "><span class="cerb-sprite sprite-forbidden"></span> {$translate->_('common.delete')|capitalize}</button>{/if}
</form>

<script type="text/javascript">
	$popup = genericAjaxPopupFetch('peek');
	$popup.one('popup_open', function(event,ui) {
		$(this).dialog('option','title',"{'projects.common.problem'|devblocks_translate}");
		$('#frmProjectProblem input:text:first').focus();
	} );
</script>
