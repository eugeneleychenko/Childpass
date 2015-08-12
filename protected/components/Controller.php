<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    public $baseUrl;

    protected function afterRender($view, &$output) {
        parent::afterRender($view,$output);
        return true;
    }

    protected function renderJSON($data)
    {
        header('Content-type: application/json');
        echo CJSON::encode($data);

        foreach (Yii::app()->log->routes as $route) {
            if($route instanceof CWebLogRoute) {
                $route->enabled = false; // disable any weblogroutes
            }
        }
        Yii::app()->end();
    }

    public function beforeRender($view) {
        $this->baseUrl = Yii::app()->request->baseUrl;
        return parent::beforeRender($view);
    }

    public function beforeAction($action)
    {
        if (!Yii::app()->user->isGuest) {
            require_once(dirname(__FILE__).'/../extensions/hoauth/models/UserOAuth.php');

            $hauth = UserOAuth::model()->getHybridAuth();
            $sessionData = $hauth::getSessionData();
            $user = User::model()->findByPk(Yii::app()->user->getId());
            $user->hauth_session_data = $sessionData;
            $user->save();
        }

        return parent::beforeAction($action);
    }
}