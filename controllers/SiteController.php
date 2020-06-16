<?php

namespace app\controllers;

use app\models\forms\ChangePasswordForm;
use app\models\forms\SignupForm;
use app\models\User;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        $success = -1;
        if ( $model->load(Yii::$app->request->post()))
        {
            $success = $model->change_password();
        }
        echo Json::encode($success);
        return $this->render('change_password',compact('model','success'));
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        Yii::$app->session['cans'] = [
            Yii::$app->getUser()->can('root'),
            Yii::$app->getUser()->can('admin'),
            Yii::$app->getUser()->can('podved')
        ];
        return $this->redirect(['app/main']);
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
        if(Yii::$app->request->get('auth_token')) {

            $signer = new Sha256();
            $token = (new Parser())->parse(Yii::$app->request->get('auth_token'));
            if ($token->verify($signer, 'ias@mirea9884')) {
                $model->username = $token->getClaim("login");
                $model->password = $token->getClaim("password");
                if ($model->validate()) {
                    Yii::$app->user->login($model->getUser());
                    return $this->redirect(['index']);
                }
                Yii::$app->session->setFlash("auth_error", "Ошибка входа!");
                return $this->redirect(['site/login']);
            }
        }
        else if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['index']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);

    }
    public function actionSignup(){
        $new_user = new SignupForm();
        if ($new_user->load(Yii::$app->request->post()) and $new_user->signup())
            return $this->redirect(['site/signup']);
        return $this->render('signup',compact('new_user'));
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


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
