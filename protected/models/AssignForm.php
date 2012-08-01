<?php
class AssignForm extends CFormModel
{
    public $userid,$items,$roles;

    public function rules()
    {
        return array(
            array('userid', 'required'),
            array('roles', 'need'),
            array('items', 'need'),
        );
    }

    public function need($attribute, $params)
    {
        if(!$this->hasErrors())
        {
           if(!$this->roles && !$this->items)
           {
             $this->addError('roles', '角色与权限项至少选一项');
           }
        }
    }

    public function attributeLabels()
    {
        return array(
            'userid' => '账户',
            'items' => '权限项',
            'roles' =>'角色',
        );
    }

    public function assign($oldRoles,$oldItems,$adminName)
    {
        $auth=Yii::app()->authManager;
        if($oldRoles)
        {
            if(!$this->roles)
            {
                $this->roles=array();
            }
            $assignRoles=array_diff($this->roles,$oldRoles);
            $revokeRoles=array_diff($oldRoles,$this->roles);
        }
        else
        {
            $assignRoles=$this->roles;
            $revokeRoles=array();
        }
        if($oldItems)
        {
            if(!$this->items)
            {
                $this->items=array();
            }
            $assignItems=array_diff($this->items,$oldItems);
            $revokeItems=array_diff($oldItems,$this->items);
            $items=array_merge($assignItems,array_intersect($this->items,$oldItems));
        }
        else
        {
            $assignItems=$this->items;
            $revokeItems=array();
            $items=$assignItems;
        }
        if($assignRoles)
        {
            foreach($assignRoles as $role)
            {
                $auth->assign($role,$adminName);
            }
        }
        if($items)
        {
          $data=$this->genData($items);
        }
        else
        {
          $data=NULL;
        }

        if($assignItems)
        {
            foreach($assignItems as $item)
            {
               $auth->assign($item,$adminName);
            }
        }
        if($revokeRoles)
        {
            foreach($revokeRoles as $role)
            {
                $auth->revoke($role,$adminName);
            }
        }
        if($revokeItems)
        {
            foreach($revokeItems as $item)
            {
                $auth->revoke($item,$adminName);
            }
        }
        return $data;
    }

    public function genData($items)
    {
      $nav=Admin::$navTree;
      foreach($nav as $k=>$item)
      {
         $pass=false;
         foreach($item['route'] as $key=>$route)
         {
             $slashPos=strpos($key,'/',1);
             $checkToken=substr($key,0,$slashPos).ucfirst(substr($key,$slashPos+1));
             if(in_array($checkToken,$items))
             {
                 $pass=true;
             }
             else
             {
                 unset($nav[$k]['route'][$key]);
             }
         }
         if($pass==false)
         {
          unset($nav[$k]);
         }
      }
      return  array('nav'=>$nav);
    }
}
?>
