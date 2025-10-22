

<input type="hidden" name="id" id="id" value="<?=!empty($material) ? encode($material->mat_id) : ''?>">
<div class="form-group">
    <label for="exampleInputEmail1">SAP Code: </label>
    <label for="" class="input-group">
        <input type="text" name="mat_sap_code" value="<?=!empty($material) ? $material->mat_sap_code : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>
<div class="form-group">
    <label for="exampleInputEmail1">ERP Code: </label>
    <label for="" class="input-group">
        <input type="text" name="mat_erp_code" value="<?=!empty($material) ? $material->mat_erp_code : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>

<div class="form-group">
    <label for="exampleInputEmail1">Material Abbr: </label>
    <label for="" class="input-group">
        <input type="text" name="mat_short_name" value="<?=!empty($material) ? $material->mat_short_name : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Material Name: </label>
    <label for="" class="input-group">
        <input type="text" name="mat_name" value="<?=!empty($material) ? $material->mat_name : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>

<div class="form-group">
	<label>Material Group:</label>
	<label for="" class="input-group">
		<select name="mat_group_id" class="form-control form-control-md dynamic_dropdown_no_order" required="true">
			<option value="-1"> Select...</option>
			<?php 
				foreach($material_groups as $row):
                $curr_mat_group_id = !empty($material) ? $material->mat_group_id : '';
				$selected = ($row->mat_group_id == $curr_mat_group_id) ? 'selected' : '';
			?>
				<option value="<?=encode($row->mat_group_id)?>" <?=$selected?>><?=$row->mat_group_name?></option>
			<?php 
				endforeach;
			?>
		</select>
	</label>
</div>

<div class="form-group">
    <label for="exampleInputEmail1">Weight: </label>
    <label for="" class="input-group">
        <input type="number" step="any" name="mat_weight" value="<?=!empty($material) ? $material->mat_weight : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>

<div class="form-group">
    <label for="exampleInputEmail1">Sales Unit: </label>
    <label for="" class="input-group">
        <input type="number" step="any" name="mat_sales_unit" value="<?=!empty($material) ? $material->mat_sales_unit : ''?>" class="form-control form-control-md" placeholder="Sales Convertion Unit" required="true">
    </label>
</div>