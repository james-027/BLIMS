

<input type="hidden" name="id" id="id" value="<?=!empty($material_brand) ? encode($material_brand->brand_id) : ''?>">
<div class="form-group">
    <label for="exampleInputEmail1">Brand Code: </label>
    <label for="" class="input-group">
        <input type="text" name="brand-code" value="<?=!empty($material_brand) ? $material_brand->brand_code : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>

<div class="form-group">
    <label for="exampleInputEmail1">Brand Name: </label>
    <label for="" class="input-group">
        <input type="text" name="brand-name" value="<?=!empty($material_brand) ? $material_brand->brand_name : ''?>" class="form-control form-control-md" required="true">
    </label>
</div>