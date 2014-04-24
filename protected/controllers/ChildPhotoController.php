<?php

class ChildPhotoController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function actions()
    {
        return array();
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to access all actions
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('delete'),
                'users'   => array('*')
            ),
            array('deny'),
        );
    }

    public function actionDelete()
    {
        $this->layout = 'ajax';

        $photoId = (int)Yii::app()->request->getPost('photo_id');

        $success = false;

        if (!empty($photoId) && ChildPhoto::model()->deleteByPk($photoId)) {
            $success = true;
        };

        $this->renderJSON(array('success' => $success));
    }
}