<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

$explodeParams = explode(',', $actionParams);
$paramsCondition = implode(' && ', $explodeParams);
$indexParam = implode(' = null, ', $explodeParams).' = null';
echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use yii\rest\Controller;
use \yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use filsh\yii2\oauth2server\filters\ErrorToExceptionFilter;
use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use OpenApi\Annotations as OA;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends Controller
{

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public $pesan = '';
    public $data = '';
    public $status = false;
    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    ['class' => HttpBearerAuth::className()],
                    ['class' => QueryParamAuth::className(), 'tokenParam' => 'accessToken'],
                ]
            ],
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pagesize = $dataProvider->pagination->pageSize;
        $total = $dataProvider->totalCount;
        
        if (count($dataProvider->getModels()) > 0){
            $this->status = true;
            $this->data = $dataProvider;
            $this->pesan = 'Data ditemukan';
        }else{
            $this->data = [];
            $this->pesan = 'Data tidak ditemukan';
        }
        
        return [
            'totalPage' => (int) (($total + $pagesize - 1) / $pagesize),
            'status' => $this->status,
            'pesan' => $this->pesan
            'data' => $this->data,
        ];
    }


    public function actionDetail(<?= $actionParams ?>){
        $model = $this->findModel(<?= $actionParams ?>);
        if ($model){
            $this->status = true;
            $this->data = $model;
            $this->pesan = 'Data ditemukan';
        }else{
            $this->data = [];
            $this->pesan = 'Data tidak ditemukan';
        }

        return[
            'status' => $this->status,
            'data' => $this->data,
            'pesan' => $this->pesan
        ];

    }

    public function actionTambah()
    {
        $model = new <?= $modelClass ?>();
        $post = Yii::$app->request->post();

        if($post && Yii::$app->api->validateFormData($post, $model->attributes())){
            $model = $this->setData($model, $post);

            if($model->save()){
                $this->status = true;
                $this->data = $model;
                $this->pesan = "Data berhasil diinput";
            }else{
                $this->pesan = ActiveForm::validate($model);
            }
        }

        return[
            'status' =>  $this->status,
            'data' => $this->data,
            'pesan' => $this->pesan
        ];

    }

    public function actionUbah(<?= $actionParams ?>){
        
        $model = $this->findModel(<?= $actionParams ?>);
        $post = Yii::$app->request->post();

        if($post && Yii::$app->api->validateFormData($post, $model->attributes())){
            $model = $this->setData($model, $post);

            if($model->save()){
                $this->status = true;
                $this->data = $model;
                $this->pesan = "Data berhasil diubah";
            }else{
                $this->pesan = ActiveForm::validate($model);
            }
        }

        return[
            'status' =>  $this->status,
            'data' => $this->data,
            'pesan' => $this->pesan,
        ];
    }

    public function actionHapus(<?= $actionParams ?>){
        $model = $this->findModel(<?= $actionParams ?>);
        if($model){
            $model->delete();
            $this->status = true;
            $this->data = $model;
            $this->pesan = 'Data berhasil dihapus';
        }
        return[
            'status' =>  $this->status,
            'data' => $this->data,
            'pesan' => $this->pesan,

        ];
    }

    public function setData($model, $data){
        foreach( $data as $key => $val){
            $model->$key = $val;
        }
        return $model;
    }

    protected function findModel(<?= $actionParams ?>)
    {
        <?php
        if (count($pks) === 1) {
            $condition = '$id';
        } else {
            $condition = [];
            foreach ($pks as $pk) {
                $condition[] = "'$pk' => \$$pk";
            }
            $condition = '[' . implode(', ', $condition) . ']';
        }
        ?>
if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
