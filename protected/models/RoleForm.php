<?php
class RoleForm extends CFormModel
{
    public $name,$description,$items;

    public function rules()
    {
        return array(
            array('name,description,items', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name' => '名称',
            'description' => '说明',
            'items' =>'操作项',
        );
    }
    public function assign($role,$oldItems)
    {
        if($role->name!=$this->name)
        {
            $role->setName($this->name);
        }
        if($role->description!=$this->description)
        {
            $role->setDescription($this->description);
        }
        if($oldItems)
        {
            if(!$this->items)
            {
                $this->items=array();
            }
            $addItems=array_diff($this->items,$oldItems);
            $removeItems=array_diff($oldItems,$this->items);
        }
        else
        {
            $addItems=$this->items;
            $removeItems=array();
        }
        if($addItems)
        {
            foreach($addItems as $item)
            {
               $role->addChild($item);
            }
        }
        if($removeItems)
        {
            foreach($removeItems as $item)
            {
               $role->removeChild($item);
            }
        }
        $role->setData($this->genData($role));
   }

   public  function genData($role)
   {
       $nav=Admin::$navTree;
       foreach($nav as $k=>$item)
       {
          $pass=false;
          foreach($item['route'] as $key=>$route)
          {
             $slashPos=strpos($key,'/',1);
             $checkToken=substr($key,0,$slashPos).ucfirst(substr($key,$slashPos+1));
              if($role->checkAccess($checkToken))
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
