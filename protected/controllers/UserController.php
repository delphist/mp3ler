<?php

/**
 * Контроллер стандартных действий с пользователем
 */
class UserController extends Controller
{
    public $layout = '//layouts/console';

    public function filters()
    {
        return array(
            'languageControl', 'languageRedirect'
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
                $this->redirect($this->createUrl('user/login'));
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

                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        $this->render('login', array(
            'model' => $model
        ));
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

                $this->redirect(Yii::app()->user->returnUrl);
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