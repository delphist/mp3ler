<?php

/**
 * Контроллер стандартных действий с пользователем
 */
class UserController extends Controller
{
    public $layout = '//layouts/console';

    public $defaultLanguage = 'en';

    public $possibleLanguages = array('en', 'ru');

    public $languageCookieName = 'partnerLanguage';

    public function filters()
    {
        return array(
            'domainControl',
            'languageControl',
            'languageRedirect',
            'counter -error'
        );
    }

    /**
     * Отправляет пользователя на нужную страницу в
     * зависимости от группы
     */
    public function actionIndex()
    {
        if(Yii::app()->user->isGuest)
        {
            $this->redirect($this->createUrl('user/login'));
        }

        switch(Yii::app()->user->model()->group)
        {
            case 'admin':
                $this->redirect($this->createUrl('console/index'));
                break;

            case 'partner':
                $this->redirect($this->createUrl('partner/index'));
                break;

            default:
                $this->redirect('/');
                break;
        }
    }

    /**
     * Страница логина
     */
    public function actionLogin()
    {
        if( ! Yii::app()->user->isGuest)
        {
            $this->redirect($this->createUrl('user/index'));
        }

        $model = new LoginForm;

        if(isset($_POST['LoginForm']))
        {
            $model->attributes = $_POST['LoginForm'];

            if($model->validate())
            {
                $model->login();

                $this->redirect(array('user/index'));
            }
        }

        $this->render('login', array(
            'model' => $model
        ));
    }

    /**
     * Страница настроек пользователя, редиректит на нужную страницу
     * в зависимости от группы пользователя
     */
    public function actionSettings()
    {
        switch(Yii::app()->user->model()->group)
        {
            case 'partner':
                $this->redirect(array('partner/settings'));
                break;

            case 'admin':
                $this->redirect(array('console/settings'));
                break;

            default:
                throw new CHttpException(404);
                break;
        }
    }

    /**
     * Страница регистрации
     */
    public function actionRegister()
    {
        if( ! Yii::app()->user->isGuest)
        {
            $this->redirect($this->createUrl('user/index'));
        }

        $model = new RegisterForm;

        if(isset($_POST['RegisterForm']))
        {
            $model->attributes = $_POST['RegisterForm'];

            /**
             * Используем название сайта в качестве логина
             */
            $model->username = $model->sitename;

            /**
             * Регистрируем как партнера
             */
            $model->group = 'partner';

            if($model->validate())
            {
                $model->save();

                $identity = new UserIdentity($model->username, $model->password);
                $identity->authenticateModel($model);
                Yii::app()->user->login($identity, NULL);

                Yii::app()->user->setFlash('success', Yii::t('app', 'Congratulations! You\'ve been succesfully registered'));

                $this->redirect(array('user/settings'));
            }
        }

        $this->render('register', array(
            'model' => $model
        ));
    }

    /**
     * Страница выхода
     */
    public function actionLogout()
    {
        if(Yii::app()->user->isGuest)
        {
            $this->redirect($this->createUrl('user/login'));
        }

        Yii::app()->user->logout();

        $this->redirect($this->createUrl('user/login'));
    }
}