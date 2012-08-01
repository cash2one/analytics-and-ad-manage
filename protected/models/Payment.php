<?php
class Payment extends CActiveRecord
{
    static public $bankList=array(
    'JUNNET-NET' => '骏网',
    'SZX-NET' => '神州行',
    'SNDACARD-NET' => '盛大卡',
    'UNICOM-NET' => '联通卡',
    'NETEASE-NET' => '网易卡',
    'TELECOM-NET' => '电信卡',
    'SPDBB2B' => '上海浦东发展银行',
    'BOCB2C' => '中国银行',
    'ICBCB2C' => '中国工商银行',
    'ICBC' => '中国工商银行',
    'CMB' => '招商银行',
    'CCB' => '中国建设银行',
    'ABC' => '中国农业银行',
    'SPDB' => '上海浦东发展银行',
    'CIB' => '兴业银行',
    'BOC' => '中国银行',
    'GDB' => '广东发展银行',
    'SDB' => '深圳发展银行',
    'CMBC' => '中国民生银行',
    'COMM' => '交通银行',
    'CITIC' => '中信银行',
    'HZCBB2C' => '杭州银行',
    'CEBBANK' => '中国光大银行',
    'CEB' => '中国光大银行',
    'SHBANK' => '上海银行',
    'NBBANK' => '宁波银行',
    'SPABANK' => '平安银行',
    'BJRCB' => '北京农村商业银行',
    'FDB' => '富滇银行',
    'CMB-DEBIT' => '招商银行',
    'CCB-DEBIT' => '中国建设银行',
    'ICBC-DEBIT' => '中国工商银行',
    'COMM-DEBIT' => '交通银行',
    'GDB-DEBIT' => '广东发展银行',
    'BOC-DEBIT' => '中国银行',
    'CEB-DEBIT' => '中国光大银行',
    'SPDB-DEBIT' => '上海浦东发展银行',
    'PSBC-DEBIT' => '中国邮政储蓄银行',
    'BJBANK' => '北京银行',
    'SHRCB' => '上海农商银行',
    '1000000-NET' => '易宝会员支付',
    'ICBC-NET-B2C' => '工商银行',
    'CMBCHINA-NET-B2C' => '招商银行',
    'ABC-NET-B2C' => '中国农业银行',
    'CCB-NET-B2C' => '建设银行',
    'BCCB-NET-B2C' => '北京银行',
    'BOCO-NET-B2C' => '交通银行',
    'CIB-NET-B2C' => '兴业银行',
    'NJCB-NET-B2C' => '南京银行',
    'CMBC-NET-B2C' => '中国民生银行',
    'CEB-NET-B2C' => '光大银行',
    'BOC-NET-B2C' => '中国银行',
    'PINGANBANK-NET' => '平安银行',
    'CBHB-NET-B2C' => '渤海银行',
    'HKBEA-NET-B2C' => '东亚银行',
    'NBCB-NET-B2C' => '宁波银行',
    'ECITIC-NET-B2C' => '中信银行',
    'SDB-NET-B2C' => '深圳发展银行',
    'GDB-NET-B2C' => '广发银行',
    'SHB-NET-B2C' => '上海银行',
    'SPDB-NET-B2C' => '上海浦东发展银行',
    'POST-NET-B2C' => '中国邮政',
    'BJRCB-NET-B2C' => '北京农村商业银行',
    'HXB-NET-B2C' => '华夏银行',
    'CZ-NET-B2C' => '浙商银行',
    'ICBC-WAP' => '工商银行WAP',
    'CMBCHINA-WAP' => '招商银行WAP',
    'CCB-WAP' => '建设银行WAP',
    'BCOM'=>'交通银行',
    'BOB'=>'北京银行',
    'CBHB'=>'渤海银行',
    'NBCB'=>'宁波银行',
    'PAB'=>'平安银行',
    'SHB'=>'上海银行',
);
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'payment';
    }

    public function rules()
    {
        return array(
            array('user_id, order_id, method_id, value, txn, token, status,platform_id,bussiness_id,method_name,user_name,server_name', 'required'),
            array('status, time', 'numerical', 'integerOnly'=>true),
            array('user_id, order_id, method_id, value', 'length', 'max'=>10),
            array('txn', 'length', 'max'=>128),
            array('token', 'length', 'max'=>256),
            array('id, user_id, order_id, method_id, value, txn, token, status, time', 'safe', 'on'=>'search'),
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'order_id' => 'Order',
            'method_id' => 'Method',
            'value' => 'Value',
            'txn' => 'Txn',
            'token' => 'Token',
            'status' => 'Status',
            'time' => 'Time',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('order_id',$this->order_id,true);
        $criteria->compare('method_id',$this->method_id,true);
        $criteria->compare('value',$this->value,true);
        $criteria->compare('txn',$this->txn,true);
        $criteria->compare('token',$this->token,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('time',$this->time);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function sumByMethod($method,$from,$to,$gameId=null,$serverId=null)
    {
        $where="where status=1 and method_id={$method}";
        if($from)
        {
          $where.=" AND time>=$from";
        }
        if($to)
        {
          $where.=" AND time<$to";
        }
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(value) FROM `payment` {$where}")->queryScalar(); 
    }

    public static function sumByServer($server,$from,$to)
    {
        $where="where status=1 and server_id={$server} and method_id<>1002";
        if($from)
        {
          $where.=" AND time>=$from";
        }
        if($to)
        {
          $where.=" AND time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(value) FROM `payment` {$where}")->queryScalar(); 
    }

    public static function sumByGame($game,$from,$to)
    {
        $where="where status=1 and game_id={$game} and method_id<>1002";
        if($from)
        {
          $where.=" AND time>=$from";
        }
        if($to)
        {
          $where.=" AND time<$to";
        }
        return (float)Yii::app()->db->createCommand("SELECT SUM(value) FROM `payment` {$where}")->queryScalar(); 
    }

    public static function paymentList($from,$to)
    {
        $where = "WHERE t.active=1 AND t.deleted=0 AND m1.enable = 1";
        $dependency=new CDbCacheDependency('SELECT count(`id`) FROM server WHERE active=1 AND deleted=0');
        $sql = "SELECT t.*,m1.name as game_name FROM `server` AS t
              LEFT JOIN `game` as m1 ON m1.id = t.game_id {$where} ORDER BY m1.id,t.id ASC";
        $rawData=Yii::app()->db->cache(1000,$dependency)->createCommand($sql)->queryAll();  

        $nowDate=strtotime(date('Y-m-d'));
        foreach($rawData as $k=>$item)
        {
          $rawData[$k]['payment']=Order::sumPaid($item['id'],$from,$to);
          $rawData[$k]['open_time']=ceil(($nowDate-$item['open_time']+1)/86400);
        }

        return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name','game_id','payment','open_time'
                            )
                        )
                   ,'pagination'=>array(
                        'pageSize'=>20
                       )
        ));
    }

    public static function combinedList($from,$to,$platformId=null,$userName=null,$gameId=null,$serverId=null,$channelId=null)
    {
        $where="WHERE t.status=1 AND t.order_id=m1.id AND t.method_id<>1002";
        if($from)
        {
            $where.=" AND t.time>={$from}";
        }
        if($to)
        {
            $where.=" AND t.time<={$to}";
        }
        if($platformId)
        {
            $where.=" AND t.platform_id ='{$platformId}'";
        }
        if($userName)
        {
            $where.=" AND t.user_name LIKE '{$userName}%'";
        }
        if($gameId)
        {
            $where.=" AND t.game_id={$gameId}";
        }
        if($serverId)
        {
            $where.=" AND t.server_id={$serverId}";
        }
        if($channelId)
        {
            $where.=" AND m1.channel_id={$channelId}";
        }
        $count=Yii::app()->db->createCommand("SELECT count(t.id) FROM payment as t,`order` as m1 {$where}")->queryScalar();
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

    public static function serverList($from,$to,$gameId=null,$serverId=null)
    {
        $where = "WHERE t.active=1 AND t.deleted=0 AND m1.enable = 1";
        if($gameId)
        {
            $where.=" AND t.game_id={$gameId}";
        }
        if($serverId)
        {
            $where.=" AND t.id={$serverId}";
        }
        $sql = "SELECT t.*,m1.name as game_name,m1.share as share FROM `server` AS t
              LEFT JOIN `game` as m1 ON m1.id = t.game_id {$where} ORDER BY m1.id,t.id ASC";
        $rawData=Yii::app()->db->createCommand($sql)->queryAll();
        foreach($rawData as $k=>$item)
        {
          $rawData[$k]['payment_user']    =Order::nbUserPaid($item['id'],$from,$to);
          $rawData[$k]['payment_count']   =Order::nbPaid($item['id'],$from,$to);
          $rawData[$k]['paid_sum']        =Order::sumPaid($item['id'],$from,$to);
          $rawData[$k]['tax_sum']         =Order::sumTax($item['id'],$from,$to);
          $rawData[$k]['real_paid']       =$rawData[$k]['paid_sum']-$rawData[$k]['tax_sum'];
          $rawData[$k]['share_sum']       =round($rawData[$k]['real_paid']-Order::sumProfit($item['id'],$from,$to),2);
        }
        return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name','game_id'
                            )
                        )
                   ,'pagination'=>array(
                        'pageSize'=>20
                       )
        ));
    }

    public static function bankList($from,$to,$gameId=null,$serverId=null, $without2144 = 1)
    {
        $where="WHERE t.status=1 AND t.order_id=m1.id AND t.method_id<>1002";
        if($from)
        {
            $where.=" AND t.time>={$from}";
        }
        if($to)
        {
            $where.=" AND t.time<={$to}";
        }
        if($gameId)
        {
            $where.=" AND t.game_id={$gameId}";
        } else if($without2144){
            $where.=" AND t.game_id NOT IN (30, 92, 108)";
        }
        if($serverId)
        {
            $where.=" AND t.server_id={$serverId}";
        }
        
        $sql="SELECT t.id,t.method_id,t.method_name,t.payment_code,count(t.id) as payment_count,sum(m1.paid) as paid_sum,
             sum(m1.payment_tax) as tax_sum FROM payment as t,`order` as m1 {$where} GROUP BY t.payment_code,t.method_id 
             ORDER BY t.method_id,t.payment_code DESC";
        $rawData=Yii::app()->db->createCommand($sql)->queryAll();
        foreach($rawData as $k=>$item)
        {
          $rawData[$k]['bank_fee']=round($item['paid_sum']*self::calculateFee($item['method_id'],$item['payment_code']),2);
          $rawData[$k]['fee_adjustment']=$item['tax_sum']-$rawData[$k]['bank_fee'];
          $rawData[$k]['bank_name']=isset(self::$bankList[$item['payment_code']])?self::$bankList[$item['payment_code']]:'直充';
        }
        return new CArrayDataProvider($rawData,array(
                    'pagination'=>false
                    ));
    }

    public static function calculateFee($methodId,$paymentCode)
    {
        $fee=0;
        if($methodId==1001)
        {
            if($paymentCode=='SZX-NET')
            {
                $fee=0.035;
            }
            else if($paymentCode=='SNDACARD-NET')
            {
                $fee=0.15;
            }
            else if($paymentCode=='NETEASE-NET')
            {
                $fee=0.15;
            }
            else
            {
                $fee=0.003;
            }
        }
        else if($methodId==1003)
        {
            $fee=0.003;
        }
        else if($methodId==1004)
        {
            $fee=0.032;
        }
        return $fee;
    }

    public static function sumFee($method,$from,$to,$gameId=null,$serverId=null)
    {
        $where="where status=1 and method_id={$method}";
        if($from)
        {
          $where.=" AND time>=$from";
        }
        if($to)
        {
          $where.=" AND time<$to";
        }
        if($gameId)
        {
          $where.=" AND game_id={$gameId}";
        }
        if($serverId)
        {
          $where.=" AND server_id={$serverId}";
        }
        $res=Yii::app()->db->createCommand("SELECT SUM(value) as paid_sum,payment_code FROM `payment` {$where} 
                GROUP BY payment_code")->queryAll();
        $fee=0;
        if($res)
        {
            foreach($res as $item)
            {
                $fee+=$item['paid_sum']*self::calculateFee($method,$item['payment_code']);
            }
        }
        return $fee;
    }

    public static function incomeList($from,$to)
    {
      $where = "";
      $sql = "SELECT * FROM `game` WHERE enable = 1 AND deleted=0 ORDER BY id ASC"; 
      $rawData=Yii::app()->db->createCommand($sql)->queryAll();
      $sum=array(
              'id'=>0,
              'name'=>'',
              'paid_sum'=>0,
              'yeepay_sum'=>0,
              '99bill_sum'=>0,
              '99szx_sum'=>0,
              'yeepay_fee'=>0,
              '99bill_fee'=>0,
              '99szx_fee'=>0,
              'yeepay_income'=>0,
              '99bill_income'=>0,
              '99szx_income'=>0,
              'income_sum'=>0
              );
      foreach($rawData as $k=>$item)
      {
        $rawData[$k]['paid_sum']    =self::sumByGame($item['id'],$from,$to);
        $rawData[$k]['yeepay_sum']  =self::sumByMethod(1001,$from,$to,$item['id']);
        $rawData[$k]['99bill_sum']  =self::sumByMethod(1003,$from,$to,$item['id']);
        $rawData[$k]['99szx_sum']  =self::sumByMethod(1004,$from,$to,$item['id']);
        $rawData[$k]['yeepay_fee']= round(self::sumFee(1001,$from,$to,$item['id']),2);
        $rawData[$k]['99bill_fee']= round(self::sumFee(1003,$from,$to,$item['id']),2);
        $rawData[$k]['99szx_fee']= round(self::sumFee(1004,$from,$to,$item['id']),2);
        $rawData[$k]['yeepay_income']=$rawData[$k]['yeepay_sum']-$rawData[$k]['yeepay_fee'];
        $rawData[$k]['99bill_income']=$rawData[$k]['99bill_sum']-$rawData[$k]['99bill_fee'];
        $rawData[$k]['99szx_income']=$rawData[$k]['99szx_sum']-$rawData[$k]['99szx_fee'];
        $rawData[$k]['income_sum']=$rawData[$k]['yeepay_income']+$rawData[$k]['99bill_income']+$rawData[$k]['99szx_income'];
        $sum['paid_sum']+=$rawData[$k]['paid_sum'];
        $sum['yeepay_sum']+=$rawData[$k]['yeepay_sum'];
        $sum['99bill_sum']+=$rawData[$k]['99bill_sum'];
        $sum['99szx_sum']+=$rawData[$k]['99szx_sum'];
        $sum['yeepay_fee']+=$rawData[$k]['yeepay_fee'];
        $sum['99bill_fee']+=$rawData[$k]['99bill_fee'];
        $sum['99szx_fee']+=$rawData[$k]['99szx_fee'];
        $sum['yeepay_income']+=$rawData[$k]['yeepay_income'];
        $sum['99bill_income']+=$rawData[$k]['99bill_income'];
        $sum['99szx_income']+=$rawData[$k]['99szx_income'];
        $sum['income_sum']+=$rawData[$k]['income_sum'];
      }
      $sum['name']='总计';
      array_push($rawData,$sum);
      return new CArrayDataProvider($rawData,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name'
                            )
                        )
                   ,'pagination'=>array(
                        'pageSize'=>20
                       )
      ));
    }

    public static function adList($from,$to,$gameId=null,$serverId=null,$channelId=null)
    {
        $where="WHERE t.status=1 AND t.order_id=m1.id AND t.method_id<>1002";
        if($from)
        {
            $where.=" AND t.time>={$from}";
        }
        if($to)
        {
            $where.=" AND t.time<={$to}";
        }
        if($gameId)
        {
            $where.=" AND t.game_id={$gameId}";
        }
        if($serverId)
        {
            $where.=" AND t.server_id={$serverId}";
        }
        if($channelId)
        {
            $where.=" AND m1.channel_id={$channelId}";
        }
        $count=Yii::app()->db->createCommand("SELECT count(t.id) FROM payment as t,`order` as m1 {$where}")->queryScalar();
        $sql="SELECT t.*,m1.create_time,m1.paid,m1.register_time,m1.channel_id FROM payment as t,`order` as m1 {$where}";
        return new CSqlDataProvider($sql,array(
                     'totalItemCount'=>$count
                    ,'sort'=>array(
                       'attributes'=>array('paid','channel_id','create_time','register_time'),
                       'defaultOrder'=>'time DESC'
                        )
                    ,'pagination'=>array(
                        'pageSize'=>20
                        )
                    ));
    }
    public static function listExport()
    {
        $dir = Yii::app()->params['offlineOrderPath'];
        chdir($dir);
        $data=array();
        foreach(glob('*.xls') as $k=>$file)
        {
            $url='/files/'.$file;
            $data[] = array('file'=>$file,'url'=>$url,'id'=>$k);
        }
        return $data;
    }
}
