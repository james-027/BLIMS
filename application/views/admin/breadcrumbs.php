<div class="page-header">
    <h4 class="page-title"><?=@$title?></h4>
    <?php if ($parent_title && $title): ?>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="<?=base_url('admin/')?>">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <?php
        if(@$menu_title):
        ?>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#"><?=@$menu_title?></a>
        </li>
        <?php
        endif;
        ?>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#"><?=@$parent_title?></a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#"><?=@$title?></a>
        </li>
    </ul>
    <?php endif; ?>
</div>