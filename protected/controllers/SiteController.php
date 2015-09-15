<?php

class SiteController extends RestController
{
    /**
     * Declares class-based actions.
     */
//    public function actions()
//    {
//        return array(
//            // captcha action renders the CAPTCHA image displayed on the contact page
//            'captcha' => array(
//                'class' => 'CCaptchaAction',
//                'backColor' => 0xFFFFFF,
//            ),
//            // page action renders "static" pages stored under 'protected/views/site/pages'
//            // They can be accessed via: index.php?r=site/page&view=FileName
//            'page' => array(
//                'class' => 'CViewAction',
//            ),
//        );
//    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('search');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionAdmin()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (!Yii::app()->user->isGuest) {
            $this->render('admin');
        } else {
            throw new CHttpException(503, "Forbidden Resource");
        }


    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }


    public function actionSearch(){
        $this->render('search');
    }

    public function actionItemlist(){
        $this->render("itemlist");
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionStatistics()
    {
        $churchcnt = Church::model()->count();
        $rschcnt = ChurchSupplies::model()->count();
        $usercnt = AppUsers::model()->count();
        $this->render('statistics', array(
            'churchcnt' => $churchcnt,
            'rsccnt' => $rschcnt,
//                'eventcnt' => $eventcnt,
            'usercnt' => $usercnt,
        ));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
//                        $this->printa($model->attributes);exit;
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect('admin');
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function login($user){
        $login = new LoginForm;
        $login->username = $user['userName'];
        $login->password = $user['password'];
        $login->rememberMe = false;
        $loginArr = array();
        if ($login->validate() && $login->login()){
            //return is logged in
            $loginArr['isLoggedIn'] = true;
            $loginArr['isGuest'] = Yii::app()->user->isGuest;
            $loginArr['userId'] = Yii::app()->user->getUser('userId');
            $loginArr['name'] = Yii::app()->user->getUser('fullname');
            //$authManager = Yii::app()->authManager;
            return $loginArr;
        }
    }

    /**
     * Retrieves all Items inside the Database
     */
    public function itemList(){
        $item = new Items;
        return $item->getItemListArr();
    }

    public function item($itemId){
        $item = new Items;
        return $item->getItemOne($itemId);
    }

    public function checkLogin(){
        $loginArr = array();
        if(Yii::app()->user->isGuest === false){
            $loginArr['isLoggedIn'] = true;
            $loginArr['isGuest'] = Yii::app()->user->isGuest;
            $loginArr['userId'] = Yii::app()->user->getUser('userId');
            $loginArr['name'] = Yii::app()->user->getUser('fullname');
            return $loginArr;
        }else{
            $loginArr['isLoggedIn'] = false;
            $loginArr['isGuest'] = Yii::app()->user->isGuest;
            $loginArr['userId'] = null;
            $loginArr['name'] = null;
            return $loginArr;
        }
    }

    public function restEvents()
    {
        $this->onRest('req.post.login.render', function ($data) {
            echo $this->restJsonEncode($this->login($data));
        });
        $this->onRest('req.get.checkLogin.render', function () {
            echo $this->restJsonEncode($this->checkLogin());
        });
        $this->onRest('req.get.itemList.render', function () {
            echo $this->restJsonEncode($this->itemList());
        });
        $this->onRest('req.get.item.render', function () {
            $itemId = $_GET['itemId'];
            echo $this->restJsonEncode($this->item($itemId));
        });
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}