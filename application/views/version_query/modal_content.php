<input type="hidden" name="id" id="id" value="<?=encode($version_query->version_query_id)?>">
<div class="form-group">
	<label>Sequence No: </label>
	<label class="input-group">
		<input type="text" name="version_query_sequence" class="form-control form-control-md" value="<?=$version_query->version_query_sequence?>" required="true">
	</label>
</div>
<div class="form-group">
	<label>Version Query Content: </label>
	<label class="input-group">
		<textarea name="version_query_content" class="form-control form-control-md" rows="4" required><?=decode($version_query->version_query_content)?></textarea>
	</label>
</div>
<div class="form-group">
	<label>Version Query Description: </label>
	<label class="input-group">
		<textarea name="version_query_desc" class="form-control form-control-md" rows="4" required><?=$version_query->version_query_desc?></textarea>
	</label>
</div>