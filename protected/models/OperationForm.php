<?php
class OperationForm extends CFormModel
{
    public $name,$description;

    public function rules()
    {
        return array(
            array('name,description', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name' => '名称',
            'description' => '说明',
        );
    }

    public function assign($item)
    {
        if($item->name!=$this->name)
        {
            $item->setName($this->name);
        }
        if($item->description!=$this->description)
        {
            $item->setDescription($this->description);
        }
    }
}
?>
