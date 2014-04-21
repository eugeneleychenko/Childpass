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
        switch ($step) {
            case 'step1':
                $form = new CForm('application.views.child.add' . ucfirst($step) . 'Form');

                $form['child']->model = new Child();

                if ($form->submitted('addStep1')) {
                    if ($form->validate()) {
                        if ($form['child']->model->save(false)) {
                            $childRelation           = new ChildRelation();
                            $childRelation->user_id  = Yii::app()->user->id;
                            $childRelation->child_id = $form['child']->model->id;
                            $childRelation->save();
                            $this->redirect(array('/child/add/step2', 'child_id' => $form['child']->model->id));
                        }
                    }
                }

                $this->render(
                    'add' . ucfirst($step), array(
                        'form' => $form,
                    )
                );
                break;
            case 'step2':
                $form = new CForm('application.views.child.add' . ucfirst($step) . 'Form');

                $form['child']->model = new ChildPhoto();
                $childId = $_GET['child_id'];

                $this->render(
                    'add' . ucfirst($step), array(
                        'form' => $form,
                    )
                );

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
                                $this->redirect(array('child/add/step3'));
                            } else {
                                $form['child']->model->addError;
                                
                                echo 'Cannot upload!';
                            }
                        }
                    }
                }

                break;
            case 'step3':
                $form = new CForm('application.views.child.add' . ucfirst($step) . 'Form');

                $form['child']->model = new Child();

                $this->render(
                    'add' . ucfirst($step), array(
                        'form' => $form,
                    )
                );
                break;
            case 'step4':
                break;
        }
    }

    public function actionList()
    {
        $userId = Yii::app()->user->getId();
        $childList = Child::model()->findAll('user_id', $userId);
        $this->render(
            'childList', array(
                'childList' => $childList,
            )
        );
    }

}