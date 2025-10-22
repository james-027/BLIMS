<?php
$userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
$btnColor = get_user_theme(array('a.userID' => $userID), true)->btnColor;
$backgroundColor = get_user_theme(array('a.userID' => $userID), true)->backgroundColor;
$topBarColor = get_user_theme(array('a.userID' => $userID), true)->topBarColor;
$logoHeaderColor = get_user_theme(array('a.userID' => $userID), true)->logoHeaderColor;
$sideBarColor = get_user_theme(array('a.userID' => $userID), true)->sideBarColor;
?>

<div class="custom-template">
    <div class="title bg-<?=$btnColor?>">UI Customization</div>
    <div class="custom-content">
        <div class="switcher">
            <div class="switch-block">
                <h4>Logo Header</h4>
                <?php
                    
                    $logoHeaderColorArray = array(
                        'dark',
                        'blue',
                        'purple',
                        'light-blue',
                        'green',
                        'orange',
                        'red',
                        'white',
                        'dark2',
                        'blue2',
                        'purple2',
                        'light-blue2',
                        'green2',
                        'orange2',
                        'red2'
                    );

                    $currentColor = $logoHeaderColor;
                    $ctr = 1;
                    $logoHeaderColorDisplay = '<div class="btnSwitch">';
                    foreach($logoHeaderColorArray as $color){
                        $selected = '';
                        if($currentColor == $color){
                            $selected = 'selected';
                        }
                        $logoHeaderColorDisplay .= '<button type="button" class="logo-header-color-switch changeLogoHeaderColor '.$selected.'" data-color="'.$color.'"></button>';

                        $ctr++;
                        if($ctr == 9){
                            $logoHeaderColorDisplay .= '</br>';
                        }
                    }
                    $logoHeaderColorDisplay .= '</div>';
                    echo $logoHeaderColorDisplay;
                ?>
                
            </div>
            <div class="switch-block">
                <h4>Navbar Header</h4>
                <?php
                    $userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
                    $colorArray = array(
                        'dark',
                        'blue',
                        'purple',
                        'light-blue',
                        'green',
                        'orange',
                        'red',
                        'white',
                        'dark2',
                        'blue2',
                        'purple2',
                        'light-blue2',
                        'green2',
                        'orange2',
                        'red2'
                    );

                    $currentColor = $topBarColor;
                    $ctr = 1;
                    $colorDisplay = '<div class="btnSwitch">';
                    foreach($colorArray as $color){
                        $selected = '';
                        if($currentColor == $color){
                            $selected = 'selected';
                        }
                        $colorDisplay .= '<button type="button" class="topbar-color-switch changeTopBarColor '.$selected.'" data-color="'.$color.'"></button>';

                        $ctr++;
                        if($ctr == 9){
                            $colorDisplay .= '</br>';
                        }
                    }
                    $colorDisplay .= '</div>';
                    echo $colorDisplay;
                ?>
            </div>
            <div class="switch-block">
                <h4>Sidebar</h4>
                <?php
                    $userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
                    $colorArray = array(
                        'dark',
                        'blue',
                        'purple',
                        'light-blue',
                        'green',
                        'orange',
                        'red',
                        'white',
                        'dark2',
                        'blue2',
                        'purple2',
                        'light-blue2',
                        'green2',
                        'orange2',
                        'red2'
                    );

                    $currentColor = $sideBarColor;;
                    $ctr = 1;
                    $colorDisplay = '<div class="btnSwitch">';
                    foreach($colorArray as $color){
                        $selected = '';
                        if($currentColor == $color){
                            $selected = 'selected';
                        }
                        $colorDisplay .= '<button type="button" class="sidebar-color-switch changeSideBarColor '.$selected.'" data-color="'.$color.'"></button>';

                        $ctr++;
                        if($ctr == 9){
                            $colorDisplay .= '</br>';
                        }
                    }
                    $colorDisplay .= '</div>';
                    echo $colorDisplay;
                ?>
                
            </div>
            <div class="switch-block">
                <h4>Background</h4>
                <?php
                    $userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
                    $colorArray = array(
                        'bg1',
                        'bg2',
                        'bg3',
                        'dark'
                    );

                    $currentColor = $backgroundColor;
                    $ctr = 1;
                    $colorDisplay = '<div class="btnSwitch">';
                    foreach($colorArray as $color){
                        $selected = '';
                        if($currentColor == $color){
                            $selected = 'selected';
                        }
                        $colorDisplay .= '<button type="button" class="background-color-switch changeBackgroundColor '.$selected.'" data-color="'.$color.'"></button>';

                        $ctr++;
                        if($ctr == 9){
                            $colorDisplay .= '</br>';
                        }
                    }
                    $colorDisplay .= '</div>';
                    echo $colorDisplay;
                ?>
                
            </div>

            <div class="switch-block">
                <h4>Buttons & Badges</h4>
                <?php
                    $userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
                    $colorArray = array(
                        'blue',
                        'light-blue',
                        'green',
                        'orange',
                        'red',
                        'purple'
                    );

                    
                    $ctr = 1;
                    $colorDisplay = '<div class="btnSwitch">';
                    if($btnColor == 'success'){
						$currentColor = 'green';
					} elseif ($btnColor == 'info'){
						$currentColor = 'light-blue';
					} elseif ($btnColor == 'primary'){
						$currentColor = 'blue';
					} elseif ($btnColor == 'warning'){
						$currentColor = 'orange';
					} elseif ($btnColor == 'danger'){
						$currentColor = 'red';
					} elseif ($btnColor == 'secondary'){
						$currentColor = 'purple';
					}
                    
                    foreach($colorArray as $color){
                        $selected = '';
                        if($currentColor == $color){
                            $selected = 'selected';
                        }
                        $colorDisplay .= '<button type="button" class="btm-color-switch changeBtnColor '.$selected.'" data-color="'.$color.'"></button>';

                        $ctr++;
                        if($ctr == 9){
                            $colorDisplay .= '</br>';
                        }
                    }
                    $colorDisplay .= '</div>';
                    echo $colorDisplay;
                ?>
                
            </div>
        </div>
    </div>
    <div class="custom-toggle btn-<?=$btnColor?>">
        <!-- <i class="flaticon-settings" data-toggle="tooltip" data-placement="left" title="UI Customization"></i> -->
        <i class="flaticon-settings"></i>
    </div>
</div>