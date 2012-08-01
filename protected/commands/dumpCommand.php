<?php
Yii::import('zii.widgets.grid.CDataColumn');
class dumpCommand extends CConsoleCommand
{
    public $objPHPExcel = null;
    public $exportType = 'Excel5';
    public $columns=array();
    public $dataProvider=array();
    public $cloumns=array();
    public $fileName='dump.xls';
    public $path=null;
    public function actionIndex($month=null)
    {
        if($month==null)
        {
          $strMonth=date('Y-m',strtotime('-1 month'));
          $to=strtotime(date('Y-m-01'));
          $from=strtotime('-1 month',$to);
          $to-=1;
        }
        else
        {
          $from=strtotime(date('Y-m-01',strtotime($month)));
          $to=strtotime('+1 month',$from)-1;
          $strMonth=date('Y-m',$from);
        }
        $this->path=Yii::app()->getBasePath().'/../files/';
        $this->columns=array(
                                array(
                                     'name'=>'platform_id'
                                    ,'type'=>'raw'
                                    ,'header'=>'平台订单号'
                                   ),
                                array(
                                    'name'=>'bussiness_id'
                                   ,'type'=>'raw'
                                   ,'header'=>'银行订单号'
                                ),
                                array(
                                    'name'=>'user_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'账户'
                                ),
                                array(
                                    'name'=>'method_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'支付方式'
                                ),
                                array(
                                    'name'=>'create_time'
                                   ,'type'=>'raw'
                                   ,'header'=>'下单时间'
                                   ,'value'=>'date("Y-m-d H:i:s",$data["create_time"])'
                                ),
                                array(
                                    'name'=>'game_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'游戏'
                                ),
                                array(
                                    'name'=>'server_name'
                                   ,'type'=>'raw'
                                   ,'header'=>'区服'
                                ),
                                array(
                                    'name'=>'paid'
                                   ,'type'=>'raw'
                                   ,'header'=>'支付金额'
                                ),
                                array(
                                    'name'=>'payment_tax'
                                   ,'type'=>'raw'
                                   ,'header'=>'充值渠道费'
                                ),
                                array(
                                    'name'=>'real_paid'
                                   ,'type'=>'raw'
                                   ,'header'=>'游戏充值金额'
                                ),
                               );
        $this->initColumns();
        $sql="SELECT * FROM game ";
        $games=Yii::app()->dbLocal->createCommand($sql)->queryAll();
        if($games)
        {
          foreach($games as $game)
          {
            $this->dataProvider=$this->_combinedList($from,$to,$game['id']);
            if($this->dataProvider->getTotalItemCount()>0)
            {
              $this->fileName="{$game['name']}充值记录输出-{$strMonth}.xls";
              $this->dataProvider->db=Yii::app()->dbLocal;
              $this->dataProvider->pagination=false;
              $this->_output();
              echo "Has been generate {$game["name"]} order item list on {$strMonth}\n ";
            }
            else
            {
                echo "There are no {$game["name"]} order item on {$strMonth} \n";
            }
          }
        }
    }

    protected function initColumns()
    {
        foreach($this->columns as $i=>$column)
        {
            if(is_string($column))
                $column=$this->createDataColumn($column);
            else
            {
                if(!isset($column['class']))
                    $column['class']='CDataColumn';
                $column=Yii::createComponent($column, $this);
            }
            if(!$column->visible)
            {
                unset($this->columns[$i]);
                continue;
            }
            if($column->id===null)
                $column->id='2133'.'_c'.$i;
            $this->columns[$i]=$column;
        }
        foreach($this->columns as $column)
            $column->init();
    }
    private function _output()
    {
        spl_autoload_unregister(array('YiiBase','autoload'));
        Yii::import('application.extensions.phpexcel.Classes.PHPExcel', true);
        $this->objPHPExcel = new PHPExcel();
        spl_autoload_register(array('YiiBase','autoload'));
        $this->objPHPExcel->getProperties()->setCreator('2133 WEB GAME');
        $this->objPHPExcel->getProperties()->setTitle('2133充值记录月报');
        $this->objPHPExcel->getProperties()->setSubject('2133充值记录月报');
        $this->objPHPExcel->getProperties()->setDescription('2133充值记录月报');
        $this->objPHPExcel->getProperties()->setCategory('');
        $this->_renderHeader();
        $this->_renderBody();
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $this->exportType);
        $objWriter->save($this->path.$this->fileName);
    }

    public function _renderHeader()
    {
        $a=0;
        foreach($this->columns as $n=>$column)
        {
            $a=$a+1;
            $head =trim($column->header)!=='' ? $column->header : $column->grid->blankDisplay;
            $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a)."1" ,$head);
        }
    }

    public function _renderBody()
    {
        $data=$this->dataProvider->getData();
        $n=count($data);
        if($n>0)
        {
            for($row=0;$row<$n;++$row)
                $this->_renderRow($row);
        }
    }

    public function _renderRow($row)
    {
        $data=$this->dataProvider->getData();
        $a=0;
        foreach($this->columns as $n=>$column)
        {
            if($column->value!==null)
                $value=$this->evaluateExpression($column->value ,array('data'=>$data[$row]));
            else if($column->name!==null)
                $value=$data[$row][$column->name];
            $value=$value===null ? "" : $value;
            $a++;
            $this->objPHPExcel->getActiveSheet()->setCellValue($this->columnName($a).($row+2) ,$value);
        }
    }

    public function _combinedList($from,$to,$gameId)
    {
        $where="WHERE t.status=1 AND t.order_id=m1.id AND t.method_id<>1002";
        $where.=" AND t.time>={$from}";
        $where.=" AND t.time<={$to}";
        $where.=" AND t.game_id={$gameId}";
        $count=Yii::app()->dbLocal->createCommand("SELECT count(t.id) FROM payment as t,`order` as m1 {$where}")->queryScalar();
        $sql="SELECT t.*,m1.create_time,m1.paid,m1.payment_tax,m1.paid-m1.payment_tax as real_paid,m1.channel_id FROM payment as t,`order` as m1 {$where}";
        return new CSqlDataProvider($sql,array(
                    'totalItemCount'=>$count
                    ,'sort'=>array(
                        'attributes'=>array('paid','payment_tax','real_paid','channel_id','create_time'),
                        'defaultOrder'=>'time DESC'
                        )
                    ,'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
    }

    public function columnName($index)
        {
            --$index;
            if($index >= 0 && $index < 26)
                return chr(ord('A') + $index);
            else if ($index > 25)
                return ($this->columnName($index / 26)).($this->columnName($index%26 + 1));
            else
                throw new Exception("Invalid Column # ".($index + 1));
        }
}
?>
