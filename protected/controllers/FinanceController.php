<?php
class FinanceController extends Controller
{
    public function actionIndex()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      $this->render('index',$data);
    }

    public function actionList()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['userName']=null;
      $data['platformId']=null;
      $data['gameId']=null;
      $data['serverId']=null;
      $data['channelId']=null;
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['user_name']))
      {
          $data['userName'] = $_GET['user_name'];
      }
      if(!empty($_GET['platform_id']))
      {
          $data['platformId'] = $_GET['platform_id'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      if(!empty($_GET['channel_id']))
      {
          $data['channelId'] = $_GET['channel_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::combinedList($from,$to,$data['platformId'],$data['userName']
              ,$data['gameId'],$data['serverId'],$data['channelId']);
      $this->render('list',$data);
    }

    public function actionGame()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['gameId']=null;
      $data['serverId']=null;
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::serverList($from,$to,$data['gameId'],$data['serverId']);
      $this->render('game',$data); 
    }

    public function actionBank()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['gameId']=null;
      $data['serverId']=null;
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::bankList($from,$to,$data['gameId'],$data['serverId']);
      $this->render('bank',$data);
    }

    public function actionIncome()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::incomeList($from,$to);
      $this->render('income',$data);
    }

    public function actionAd()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['gameId']=null;
      $data['serverId']=null;
      $data['channelId']=null;

      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      if(!empty($_GET['channel_id']))
      {
          $data['channelId'] = $_GET['channel_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::adList($from,$to,$data['gameId'],$data['serverId'],$data['channelId']);
      $this->render('ad',$data); 
    }
    
    public function actionExportAd()
    {
        $data['endDate'] = date('Y-m-d');
        $data['startDate'] = date('Y-m-d');
        $data['gameId']=null;
        $data['serverId']=null;
        $data['channelId']=null;
        
        if(!empty($_GET['start_date']))
        {
            $data['startDate'] = $_GET['start_date'];
        }
        if(!empty($_GET['end_date']))
        {
            $data['endDate'] = $_GET['end_date'];
        }
        if(!empty($_GET['game_id']))
        {
            $data['gameId'] = $_GET['game_id'];
        }
        if(!empty($_GET['server_id']))
        {
            $data['serverId'] = $_GET['server_id'];
        }
        if(!empty($_GET['channel_id']))
        {
            $data['channelId'] = $_GET['channel_id'];
        }
        $from=strtotime($data['startDate']);
        $to=strtotime($data['endDate'])+86399;
        $data['dataProvider']=Payment::adList($from,$to,$data['gameId'],$data['serverId'],$data['channelId']);
        $this->render('_exportAd',$data);
    }

    public function actionExportGame()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['gameId']=null;
      $data['serverId']=null;
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::serverList($from,$to,$data['gameId'],$data['serverId']);
      $this->render('_exportGame',$data);
    }

    public function actionExportBank()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['gameId']=null;
      $data['serverId']=null;
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::bankList($from,$to,$data['gameId'],$data['serverId']);
      $this->render('_exportBank',$data); 
    }

    public function actionExportIncome()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::incomeList($from,$to);
      $this->render('_exportIncome',$data);
    }

    public function actionExportList()
    {
      $data['endDate'] = date('Y-m-d');
      $data['startDate'] = date('Y-m-d');
      $data['userName']=null;
      $data['platformId']=null;
      $data['gameId']=null;
      $data['serverId']=null;
      $data['channelId']=null;
      if(!empty($_GET['start_date']))
      {
          $data['startDate'] = $_GET['start_date'];
      }
      if(!empty($_GET['end_date']))
      {
          $data['endDate'] = $_GET['end_date'];
      }
      if(!empty($_GET['user_name']))
      {
          $data['userName'] = $_GET['user_name'];
      }
      if(!empty($_GET['platform_id']))
      {
          $data['platformId'] = $_GET['platform_id'];
      }
      if(!empty($_GET['game_id']))
      {
          $data['gameId'] = $_GET['game_id'];
      }
      if(!empty($_GET['server_id']))
      {
          $data['serverId'] = $_GET['server_id'];
      }
      if(!empty($_GET['channel_id']))
      {
          $data['channelId'] = $_GET['channel_id'];
      }
      $from=strtotime($data['startDate']);
      $to=strtotime($data['endDate'])+86399;
      $data['dataProvider']=Payment::combinedList($from,$to,$data['platformId'],$data['userName']
              ,$data['gameId'],$data['serverId'],$data['channelId']);
      $this->render('_exportList',$data);
    }

    public function actionExportOffline()
    {
        $data=Payment::listExport();
        $dataProvider=new CArrayDataProvider($data,array(
                    'sort'=>array(
                        'attributes'=>array(
                            'id','name'
                            )
                        )
                   ,'pagination'=>array(
                        'pageSize'=>20
                       )
      ));
      $this->render('offline',array('dataProvider'=>$dataProvider));
    }
}
