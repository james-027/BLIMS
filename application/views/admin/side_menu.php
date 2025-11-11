<?php include_once('side_menu_cond.php');?>

<div class="sidebar sidebar-style-2"
    data-background-color="<?=get_user_theme(array('a.userID' => $userID), true)->sideBarColor;?>">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">

        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="<?=@$profile['profile_img_link']?>" alt="..." class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#userInfo" aria-expanded="true">
                        <span>
                            <font <?=$userFullNameToolTip?>> <?=$userFullName?> </font>
                            <span class="user-level" <?=$userTypeToolTip?>><?=$userTypeName?></span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="userInfo">
                        <ul class="nav">
                            <li>
                                <a href="<?=base_url('admin/my-profile')?>">
                                    <span class="link-collapse">My Profile</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?=base_url('admin/activity-logs')?>">
                                    <span class="link-collapse">Activity Logs</span>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="notif-item">
                                    <span class="link-collapse">Notifications</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=base_url('admin/my-profile#change-pass')?>" class="refer-link">
                                    <span class="link-collapse">Change Password</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=base_url('admin/my-profile#contact-us')?>" class="refer-link">
                                    <span class="link-collapse">Contact Us</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-<?=$btnColor?>">
                <?php
                // $added_class = $this->uri->segment(1) == 'admin' && ($this->uri->segment(2) == '' || $this->uri->segment(2) == 'dashboard') ? ' active' : '';
                $added_class = ($this->uri->segment(1) == 'admin' && ($this->uri->segment(2) == '' || $this->uri->segment(2) == 'dashboard')) || $this->uri->segment(1) == 'incentive-details' ? ' active' : '';
                
                ?>
                <li class="nav-item <?=$added_class?>">

                    <a href="<?=base_url('admin')?>">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php
                $parentLinkArray = array(
                    array('1' => 'registration'),
                    array('1' => 'verification'),
                    array('1' => 'initialpreparation'),
                    array('1' => 'finalpreparation'),
                    array('1' => 'testexecution'),
                    array('1' => 'datareview'),
                    array('1' => 'resultverification'),
                );
                $parentAliasArray = array(
                    'registration',
                    'verification',
                    'initialpreparation',
                    'finalpreparation',
                    'testexecution',
                    'datareview',
                    'resultverification',
                );
                echo menu_link_to_display($parentLinkArray, $parentAliasArray, 'Transactional', 1, 'prefix', 'fa-layer-group');
                    echo menu_link_to_display('registration', 'registration', null, 1);
                    echo menu_link_to_display('verification', 'verification', null, 1);
                    echo menu_link_to_display('initialpreparation', 'initialpreparation', null, 1);
                    echo menu_link_to_display('finalpreparation', 'finalpreparation', null, 1);
                    echo menu_link_to_display('testexecution', 'testexecution', null, 1);
                    echo menu_link_to_display('datareview', 'datareview', null, 1);
                    echo menu_link_to_display('resultverification', 'resultverification', null, 1);
                echo menu_link_to_display($parentLinkArray, $parentAliasArray, 'Transactional', 1, 'suffix');
                
                $parentLinkArray = array(
                    array('2' => 'branch'),
                    array('2' => 'laboratories'),
                    array('2' => 'supplier'),
                    array('2' => 'analyst'),
                    array('2' => 'internal-feedmill'),
                    array('2' => 'commercial-feedmill'),
                    array('2' => 'sample-name'),
                    array('2' => 'sample-type'),
                    array('2' => 'lab-test-group'),
                    array('2' => 'lab-test'),
                    array('2' => 'animal-nutritionist'),
                    array('2' => 'nutritionist'),
                    array('2' => 'test'),
                    array('2' => 'animal-feed'),
                    array('2' => 'region'),
                    array('2' => 'users'),
                    array('2' => 'test-parameter'),
                    array('2' => 'test-name'),
                    array('2' => 'test-method'), 
                    array('2' => 'ref-method'), 
                    array('2' => 'user-key'),
                    array('2' => 'user-roles'),
                    array('2' => 'batch-number'),
                    array('2' => 'plate-number'),
                    array('2' => 'reason'),
                    array('2' => 'roles'),
                    array('2' => 'user-sloc'),
                    array('2' => 'sys-modules'),
                    array('2' => 'key'),
                    array('2' => 'logs'),
                );
                $parentAliasArray = array(
                    'users',
                    'user_key',
                    'user_roles',
                    'roles',
                    'user_sloc',
                    'sys_modules',
                    'system_key',
                    'logs',
                );
                echo menu_link_to_display($parentLinkArray, $parentAliasArray, 'Master Data', 1, 'prefix', 'fa-th-list');
                    $linkArray = array(
                                        'lab-test',
                                        'analyst',
                                        'supplier',
                                        'batch-number',
                                        'plate-number',
                                        'reason',
                                      );   
                    $aliasArray = array(
                                        'lab-test',
                                        'analyst',
                                        'supplier',
                                        'batch-number',
                                        'plate-number',
                                        'reason',
                                      );  
                    echo menu_link_to_display($linkArray, $aliasArray, 'Laboratory Config', 2, 'complex');

                    $linkArray = array(
                                        'test',
                                        'test-name',
                                        'lab-test-group',
                                        'test-parameter',
                                        'test-method',
                                        'ref-method',
                                      );   
                    $aliasArray = array(
                                        'test',
                                        'test-name',
                                        'lab-test-group',
                                        'test-parameter',
                                        'test-method',
                                        'ref-method',
                                      );  
					echo menu_link_to_display($linkArray, $aliasArray, 'Test Config', 2, 'complex');


                    $linkArray = array(
                                        'sample-name',
                                        'sample-type',
                                      );   
                    $aliasArray = array(
                                        'sample-name',
                                        'sample-type',
                                      );  
					echo menu_link_to_display($linkArray, $aliasArray, 'Samples Config', 2, 'complex');

                    $linkArray = array(
                                        'animal-feed',
                                        'nutritionist',
                                        'animal-nutritionist',
                                      );   
                    $aliasArray = array(
                                        'animal-feed',
                                        'nutritionist',
                                        'animal-nutritionist'
                                      );  
					echo menu_link_to_display($linkArray, $aliasArray, 'Nutritionist Config', 2, 'complex');
                    
					$linkArray = array('laboratories','branch', 'region');
					$aliasArray = array('laboratories','business_center', 'region');
					echo menu_link_to_display($linkArray, $aliasArray, 'Location Config', 2, 'complex');

                    
					$linkArray = array(
                                        'internal-feedmill',
                                        'commercial-feedmill',
                                    );
					$aliasArray = array(    
                                        'internal-feedmill',
                                        'comm-feedmill',
                                    );
					echo menu_link_to_display($linkArray, $aliasArray, 'Feedmill Config', 2, 'complex');
                    
                    $linkArray = array(
                        'users',
                        'user-roles',
                        'user-key',
                        'user-sloc',
                        'roles',
                    );
                    $aliasArray = array(
                        'users',
                        'user_roles',
                        'user_key',
                        'user_sloc',
                        'roles',
                    );
                    echo menu_link_to_display($linkArray, $aliasArray, 'Users Config', 2, 'complex');

                    $linkArray = array(
                        'key',
                        'sys-modules',
                        'logs'
                    );
                    $aliasArray = array(
                        'system_key',
                        'sys_modules',
                        'logs'
                    );
                    echo menu_link_to_display($linkArray, $aliasArray, 'System Config', 2, 'complex');
     
                echo menu_link_to_display($parentLinkArray, $parentAliasArray, 'Master Data', 1, 'suffix');



                $parentLinkArray = array(
                    array('2' => 'my-profile'),
                    array('2' => 'notifications'),
                    array('2' => 'activity-logs'),
                    array('2' => 'user-feedback'),
                    array('1' => 'incentive-error-logs'),
                    array('1' => 'api-pos-logs'),
                    array('1' => 'api-sync-errors'),
                );
                $parentAliasArray = array(
                    'my_profile',
                    'activity_logs',
                    'user_feedback',
                    'notification',
                    'incentive_error_logs',
                    'api_pos_logs',
                    'api_sync_errors',
                );
                echo menu_link_to_display($parentLinkArray, $parentAliasArray, 'Extras', 1, 'prefix', 'fa-bars');

                    echo menu_link_to_display('my-profile', 'my_profile', null, 2);
                    echo menu_link_to_display('activity-logs', 'activity_logs', null, 2);
                    echo menu_link_to_display('user-feedback', 'user_feedback', null, 2);

                    $added_class = $this->uri->segment(2) == 'notifications' ? ' active' : '';
                    echo '<li class="'.$added_class.'">
                        <a href="#" class="notif-item">
                            <span class="sub-item">Notifications</span>
                        </a>
                    </li>';

                    echo menu_link_to_display('incentive-error-logs', 'incentive_error_logs', null, 1);
                    echo menu_link_to_display('api-pos-logs', 'api_pos_logs', null, 1);
                    echo menu_link_to_display('api-sync-errors', 'api_sync_errors', null, 1);

                echo menu_link_to_display($parentLinkArray, $parentAliasArray, 'Extras', 1, 'suffix');


                ?>
            </ul>

        </div>

        <?php $this->load->view('admin/footer')?>
    </div>
</div>