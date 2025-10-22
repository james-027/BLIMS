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
                    <?php
                        if($this->session->flashdata('message') != "" ){
                            echo $this->session->flashdata('message');
                        }
                    ?>
                
                    <?=$new_button?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-sys-logs">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>ID</th>
                                    <th>Page</th>
                                    <th>Log Detail</th>
                                    <th>User</th>
                                    <th>Registered Time</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>