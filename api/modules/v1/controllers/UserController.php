<?php

namespace api\modules\v1\controllers;
use yii;
use yii\rest\ActiveController;
use yii\db\Query;

/**
 * User Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class UserController extends ActiveController
{
	
    public $modelClass = 'api\modules\v1\models\User';    

	/* Declare actions supported by APIs (Added in api/modules/v1/components/controller.php too) */
    public function actions(){
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    /* Declare methods supported by APIs */
    protected function verbs(){
        return [
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH','POST'],
            'delete' => ['DELETE'],
            'view' => ['GET'],
            'index'=>['GET'],
        ];
    }
	public function actionIndex()
		{
 
          $params=$_REQUEST;
          $filter=array();
          $sort="";
 
          $page=1;
          $limit=10;
 
           if(isset($params['page']))
             $page=$params['page'];
 
 
           if(isset($params['limit']))
              $limit=$params['limit'];
 
            $offset=$limit*($page-1);
 
 
            /* Filter elements */
           if(isset($params['filter']))
            {
             $filter=(array)json_decode($params['filter']);
            }
 
             if(isset($params['datefilter']))
            {
             $datefilter=(array)json_decode($params['datefilter']);
            }
 
 
            if(isset($params['sort']))
            {
              $sort=$params['sort'];
         if(isset($params['order']))
        {  
            if($params['order']=="false")
             $sort.=" desc";
            else
             $sort.=" asc";
 
        }
            }

               $query=new Query;
               $query->offset($offset)
                 ->limit($limit)
                 ->from('user')
                 ->andFilterWhere(['like', 'id', @$filter['id']])
                 ->andFilterWhere(['like', 'name', @$filter['name']])
                 ->andFilterWhere(['like', 'age', @$filter['age']])
                 ->orderBy($sort)
                 ->select("id,name,age,createdAt,updatedAt");
 
           if(@$datefilter['from'])
           {
            $query->andWhere("createdAt >= '".$datefilter['from']."' ");
           }
           if(@$datefilter['to'])
           {
            $query->andWhere("createdAt <= '".$datefilter['to']."'");
           }
           $command = $query->createCommand();
               $models = $command->queryAll();
 
               $totalItems=$query->count();
 
          $this->setHeader(200);
 
          echo json_encode(array('status'=>1,'data'=>$models,'totalItems'=>$totalItems),JSON_PRETTY_PRINT);
 
		}
		
		public function actionView($id)
			  {
			 
				//$model=$this->findModel($id);
				$rows = (new \yii\db\Query())
						->select('*')
						->from('user')
						->where(['id' => $id])
						->one();
				//print_r($rows );die;
				$this->setHeader(200);
				echo json_encode(array('status'=>1,'data'=>array_filter($rows)),JSON_PRETTY_PRINT);
			 
			  } 
		  
		public function actionUpdate($id){
			 $params=$_REQUEST;	
			 $date = new \DateTime();
             $timestamp = $date->getTimestamp();
			 $params['updatedAt']= $timestamp;
			if(Yii::$app->db->createCommand()
				 ->update('user',$params,["id"=>$id] )
				 ->execute()){
					 $this->setHeader(200);
					//echo json_encode(array('status'=>1,'data'=>array_filter($params)),JSON_PRETTY_PRINT);
					echo $this->actionView($id);
				 }
				 
				 
		}
		
		public function actionCreate(){
			$params=$_REQUEST;
			
			 $date = new \DateTime();
             $timestamp = $date->getTimestamp();
			 $params['createdAt']= $timestamp;
			if(Yii::$app->db->createCommand()
				 ->insert('user',$params)->execute()){
					$this->setHeader(200);
					echo json_encode(array('status'=>1,'data'=>array_filter($params)),JSON_PRETTY_PRINT);
					 
				 }
			
				 
				 
		}
		
		 /**
			* Deletes an existing User model.
			* @param integer $id
			* @return json
			*/
			public function actionDelete($id)
			{
			if(Yii::$app->db->createCommand()->delete('user', 'id=:id', array(':id'=>$id))->execute())
			{ 
				//$model=json_decode($this->actionIndex());
				// $this->setHeader(200);
				// echo json_encode(array('status'=>1,'data'=>$model),JSON_PRETTY_PRINT);
				echo $this->actionIndex();
			}
			else
			{
		 
				$this->setHeader(400);
				echo json_encode(array('status'=>0,'error_code'=>400,'errors'=>"error"),JSON_PRETTY_PRINT);
			}
		 
			}
          /* Functions to set header with status code. eg: 200 OK ,400 Bad Request etc..*/      
	private function setHeader($status)
	  {
	 
		  $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		  $content_type="application/json; charset=utf-8";
	 
		  header($status_header);
		  header('Content-type: ' . $content_type);
		  header('X-Powered-By: ' . "Nintriva <nintriva.com>");
	  }
  private function _getStatusCodeMessage($status)
  {
      $codes = Array(
      200 => 'OK',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      );
      return (isset($codes[$status])) ? $codes[$status] : '';
  }
}
