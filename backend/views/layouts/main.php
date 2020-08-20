<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
// use yii\bootstrap\Nav;
// use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use kartik\growl\Growl;
use kartik\sidenav\SideNav;
use backend\assets\AppAsset;
use kartik\icons\Icon;
use app\models\Patches\Patch;
use lo\widgets\modal\ModalAjax;
use app\models\Accounts;

 
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?php
   
    echo ModalAjax::widget([
    'id' => 'update',
    'selector' => 'a.profile', // all buttons in grid view with href attribute

    'options' => ['class' => 'header-success'],
    'pjaxContainer' => '#grid-menu-pjax',
    'autoClose' => true,
    'ajaxSubmit' => true,
    'size' => ModalAjax::SIZE_LARGE,
    'events'=>[
        ModalAjax::EVENT_MODAL_SHOW => new \yii\web\JsExpression("
            function(event, data, status, xhr, selector) {
                selector.addClass('warning');
            }
       "),
       
     
    ]
]);


    ?>
</head>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<body>
<style type="text/css">

     
</style>
<?php $this->beginBody() ?>

 <div id="page-wrapper">
    
            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">
                <!-- Alternative Sidebar -->
                <div id="sidebar-alt">
                    <!-- Wrapper for scrolling functionality -->
                  
                    <!-- END Wrapper for scrolling functionality -->
                </div>
            

                <!-- END Alternative Sidebar -->

                <!-- Main Sidebar -->
                <div id="sidebar">

                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Brand -->
                            <a href="#" class="sidebar-brand" data-toggle="tooltip" title="Hooray!">
                               <span><strong>Cesscolina  </strong>EMS</span>
                            </a>

                          
                            <!-- END Brand -->

                            <!-- User Info -->
                            
                            <!-- END User Info -->

                            <!-- Theme Colors -->
                            <!-- Change Color Theme functionality can be found in js/app.js - templateOptions() -->
                           
                            <!-- END Theme Colors -->

                            <!-- Sidebar Navigation -->
                           
                            
                            <?php
                                echo SideNav::widget([
                                'items' => Patch::Menus(),


                                ]);
                                
                                ?>
                           
                            <!-- END Sidebar Navigation -->

                            <!-- Sidebar Notifications -->
                  
                            <!-- END Sidebar Notifications -->
                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
                <!-- END Main Sidebar -->

                <!-- Main Container -->
                <div id="main-container">

                    <!-- Header -->
                    <!-- In the PHP version you can set the following options from inc/config file -->
                    <!--
                        Available header.navbar classes:

                        'navbar-default'            for the default light header
                        'navbar-inverse'            for an alternative dark header

                        'navbar-fixed-top'          for a top fixed header (fixed sidebars with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                            'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added




                        'navbar-fixed-bottom'       for a bottom fixed header (fixed sidebars with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                            'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
                    -->
                    <header class="navbar navbar-default">
                        <!-- Left Header Navigation -->
                      <ul class="nav navbar-nav-custom">
                            <!-- Main Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                    <i class="fa fa-bars fa-fw"></i>
                                </a>
                            </li>
                        </ul>
                        <!-- END Left Header Navigation -->

                        <!-- Search Form -->
                        
                        <!-- END Search Form -->

                        <!-- Right Header Navigation -->
                        <ul class="nav navbar-nav-custom pull-right">
                            <!-- Alternative Sidebar Toggle Button -->
                           
                            <!-- END Alternative Sidebar Toggle Button -->

                            <!-- User Dropdown -->
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="../assets/proui/img/placeholders/avatars/avatar2.jpg" alt="avatar"> <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                    <li class="dropdown-header text-center"><strong><?=\Yii::$app->user->identity->username ?></strong></li>
                               
                                    <li>
                                        <?php
                                    $modelUser = Accounts::find()->where(['id'=>\Yii::$app->user->identity->id])->all();
                                        foreach ($modelUser as $value) {
                                            $id = $value['id'];
                                        }
                                    echo Html::a('Profile', Url::to(['/accounts/profile', 'id' => $id]), [
                                    'class' => 'profile',

                                    //'title'=>'Update',

                                    ]);
                                    ?>
                                        <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
                                   <!--      <a href="package.devs/package/index" data-toggle="modal">
                                            <i class="fa fa-cog fa-fw pull-right"></i>
                                            Settings
                                        </a> -->
                                    </li>
                                    <li class="divider"></li>
                                    <li>
   
                                        
                                 <?= Html::a('Logout', ['site/logout'], ['data' => ['method' => 'post']]) ?>
                                      
                                    </li>
                                   
                                </ul>
                            </li>
                            <!-- END User Dropdown -->
                        </ul>
                        <!-- END Right Header Navigation -->
                    </header>


             <div id="page-content">
                    <!-- Header -->
                       <?php
  
  
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
 
   
    ?>

    
        <?= Breadcrumbs::widget([
            'homeLink'=>[
                            'label'=>Yii::t('yii', 'Dashboard'),
                            'url'=>['/dashboard/index'],
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        
           <?php foreach (Yii::$app->session->getAllFlashes() as $message):;  ?>
        <?php

        echo Growl::widget([
            'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
            //'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            //'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
            'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
            'showSeparator' => true,
            'delay' => 1, //This delay is how long before the message shows
            'pluginOptions' => [
                'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                'placement' => [
                    'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                    'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                ]
            ],
            'useAnimation'=>true
        ]);
        ?>
    <?php endforeach; ?>
   
        <?= $content ?>
</div>
 <footer class="clearfix">
                        <div class="pull-right">
                            Crafted with <i class="fa fa-heart text-danger"></i> by <a href="http://goo.gl/vNS3I" target="_blank">pixelcave</a>
                        </div>
                        <div class="pull-left">
                            <span id="year-copy"></span> &copy; <a href="http://goo.gl/TDOSuC" target="_blank">ProUI 3.6</a>
                        </div>
                    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
