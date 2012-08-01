   <!--  start nav-outer-repeat................................................................................................. START -->
     <div class="nav-outer-repeat">
     <!--  start nav-outer -->
       <div class="nav-outer">
       <!-- start nav-right -->
          <div id="nav-right">
            <div class="showhide-account">
            <a href="/admin/update">
                <img src="/images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" />
            </a>
            </div>
            <a href="/site/logout" id="logout"><img src="/images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
            <div class="clear">&nbsp;</div>
        </div>
        <span class="loginname"><strong><?php echo Yii::app()->user->getName()?></strong>，欢迎你！</span>
        <!-- end nav-right -->
        <!--  start nav -->
        <a href="/" class="logocss"><img src="/images/logo.jpg"  alt="2133" /></a>
        <div class="nav">

          <div class="table">
          <?php
          $auth=Yii::app()->authManager;
          $admin=Admin::model()->findByPk(Yii::app()->user->getId());
          $roles=$auth->getRoles(Yii::app()->user->getName());
          $nav=array();
          foreach($roles as $role)
          {
           $data=$role->getData();
           $nav+=$data['nav'];
          }
          
          if($admin)
          {
            $adminData=$admin->getData();
            if($adminData && isset($adminData['nav']))
            {
                $_ = array();
                foreach(Admin::$navTree as $k => $v)
                {
                    if(isset($nav[$k]) || isset($adminData['nav'][$k]))
                    {
                        $_[$k] = isset($nav[$k]) ? $nav[$k] : $adminData['nav'][$k];
                        unset($_[$k]['route']);
                        foreach($v['route'] as $a => $r)
                        {
                            if(isset($nav[$k]['route'][$a]) || isset($adminData['nav'][$k]['route'][$a]))
                                $_[$k]['route'][$a] = $r;
                        }
                    }
                }
            }
          }
          $nav = isset($_) ? $_ : $nav;
          ksort($nav);
          
          foreach($nav as $k=>$item)
          {
            $current='select';
            $show='';
            if(in_array($this->id,$item['id']))
            {
             $current='current';
             $show='show';
            }

            $url='/';
            foreach($item['route'] as $route=>$name)
            {
                $url.=$route;
                break;
            }
            echo"<ul class='{$current}'> 
                <li><a href='{$url}' class='X_nav'><b><i>{$item['name']}</i></b><!--[if IE 7]><!--></a><!--<![endif]--> ";
            echo "<!--[if lte IE 6]><table><tr><td><![endif]-->
                <div class='select_sub {$show}'>
                <ul class='sub'>";
            foreach($item['route'] as $route=>$name)
            {
                $subShow='';
                if($this->route==$route)
                {
                    $subShow='sub_show';
                }
                echo "<li class='{$subShow}'><a href='/{$route}'>{$name}</a></li>";
            }
            echo " </ul></div> <!--[if lte IE 6]></td></tr></table></a><![endif]--></li> </ul>";
          }
?>
<?php if(in_array(Yii::app()->user->getId(), array(1, 4, 49))):?>
<ul class="<?php echo $this->id=='admin'?'current':'select'; ?>">
<li><a href="/admin/index" class="X_nav"><b><i>管理员管理</i></b><!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->
<div class="select_sub <?php echo $this->id=='admin'?'show':''; ?>">
<ul class="sub">
<li <?php if($this->route=='admin/index')echo 'class="sub_show"'; ?>><a href="/admin/index">管理员列表</a></li>
<li <?php if($this->route=='admin/create')echo 'class="sub_show"'; ?>><a href="/admin/create">添加管理员</a></li>
<li <?php if($this->route=='admin/itemList')echo 'class="sub_show"'; ?>><a href="/admin/itemList">权限项列表</a></li>
<li <?php if($this->route=='admin/roleList')echo 'class="sub_show"'; ?>><a href="/admin/roleList">角色列表</a></li>
<li <?php if($this->route=='admin/item')echo 'class="sub_show"'; ?>><a href="/admin/item">添加权限项</a></li>
<li <?php if($this->route=='admin/role')echo 'class="sub_show"'; ?>><a href="/admin/role">添加角色</a></li>
</ul>
</div>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>
</ul>
<?php endif;?>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<!--  start nav -->
</div>
<div class="clear"></div>
<!--  start nav-outer -->
</div>
<!--  start nav-outer-repeat................................................... END -->
<div class="clear"></div>
