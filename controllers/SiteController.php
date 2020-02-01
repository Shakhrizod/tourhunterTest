<?php

namespace app\controllers;

use app\models\searches\UsersSearch;
use app\models\TransferForm;
use app\models\User;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\widgets\ActiveForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
//            check if user exists
            $user = Users::findByUsername($model->username);
//            if not exist, create user
            if (!isset($user)){
                $user = new Users();
                $user->username = $model->username;
                $user->save();
            }

            if ($model->login())
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAbout()
    {
        $model = new TransferForm();
        if (Yii::$app->request->isAjax && $model->load($_POST)){
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())){
            $transaction = Yii::$app->db->beginTransaction();

            try{
                $user = Users::findByUsername($model->username);
                $user->balance += $model->amount;
                $user->save();

                $currentUser = Users::findByUsername(Yii::$app->user->identity->username);
                $currentUser->balance -= $model->amount;
                $currentUser->save();
                $transaction->commit();
            }catch (\Exception $exception){
                $transaction->rollBack();
            }
            return $this->refresh();
        }
        return $this->render('about',[
            'model' => $model
        ]);
    }
}
