<?php

class ChildController extends Controller
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
                  'actions' => array('add'),
                  'users'   => array('*')
            ),
            array('deny'),
        );
    }

    public function actionAdd($step = 'step1')
    {
        $form = new CForm('application.views.child.add' . ucfirst($step) . 'Form');

        $childId = isset($_GET['child_id']) ? $_GET['child_id'] : false;

        $data = array();

        switch ($step) {
            case 'step1':
                if ($childId) {
                    $form['child']->model = Child::model()->findByPk($childId);
                } else {
                    $form['child']->model = new Child();
                }

                if ($form->submitted('addStep1')) {
                    $form['child']->model->user_id = Yii::app()->user->getId();
                    if ($form->validate()) {
                        if ($form['child']->model->save(false)) {
                            $this->redirect(array('/child/add/step2', 'child_id' => $form['child']->model->id));
                        }
                    }
                }

                break;
            case 'step2':
                $form['child']->model = new ChildPhoto();

                $childId = $_GET['child_id'];

                if ($form->submitted('addStep2') && $childId) {
                    $images = CUploadedFile::getInstances($form['child']->model, 'image');

                    if (!empty($images)) {
                        $savePath = Yii::getPathOfAlias('webroot') . "/children/{$childId}/photos/";
                        if (!is_dir($savePath)) mkdir($savePath, 0777, true);
                        foreach ($images as $image => $pic) {

                            if ($pic->saveAs($savePath . $pic->name)) {
                                $photo           = $form['child']->model;
                                $photo->image    = $pic;
                                $photo->filename = $pic->name;
                                $photo->child_id = $childId;
                                $photo->save();
                            } else {
                                $form['child']->model->addError;
                                
                                echo 'Cannot upload!';
                            }
                        }
                    }
                    $this->redirect(array('child/add/step3'));
                } else {
                    $childPhotos = ChildPhoto::model()->findAll('child_id = :child_id', array(':child_id' => $childId));
                    foreach ($childPhotos as $photo) {
                        $photo->filename = Yii::app()->request->getBaseUrl(true).'/children/'.$childId.'/photos/'.$photo->filename;
                    }
                    $data['childPhotos'] = $childPhotos;
                }

                break;
            case 'step3':
                $form['child']->model = new Child();

                break;
            case 'step4':
                break;
        }

        $this->render(
            'add' . ucfirst($step), array(
                'form' => $form,
                'data' => $data
            )
        );
    }

    public function actionList()
    {
        $userId = Yii::app()->user->getId();

        $childList = Child::model()->with(
            array('childPhotos' => array(
                'select' => array('filename'),
                'joinType' => 'LEFT JOIN',
                'order' => 'is_main DESC'
            ),
            ))->findAll('user_id = :user_id', array(':user_id' => $userId));

        foreach ($childList as &$child) {
            if (isset($child->childPhotos[0])) {
                foreach ($child->childPhotos as $key => $photo) {
                    if (!$key) {
                        $photo->filename = Yii::app()->request->getBaseUrl(true).'/children/'.$child->id.'/photos/'.$photo->filename;
                    }
                }
            } else {

            }

        }

        $this->render(
            'childList', array(
                'childList' => $childList,
            )
        );
    }

}