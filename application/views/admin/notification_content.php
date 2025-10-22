<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>
<div class="page-inner animated fadeInRightBig">
    
    <?=$breadcrumbs?>            
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                
                <div class="card-body">
                    
                    <?=$new_button?>
                    <input type="hidden" value="<?=$param1?>" id="param1">
                    <input type="hidden" value="<?=$param2?>" id="param2">
                    <input type="hidden" value="<?=$param3?>" id="param3">
                    <input type="hidden" value="<?=$param4?>" id="param4">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover tbl-view-notif" style="width:100%">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Notif ID</th>
                                    <th>Notification</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                    
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</div>