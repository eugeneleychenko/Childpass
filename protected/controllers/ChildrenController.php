<?php

class ChildrenController extends Controller
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

    public function actionAdd($step)
    {
        switch ($step) {
            case 'step1':
                $form = new CForm('application.views.children.add' . ucfirst($step) . 'Form');

                $form['children']->model = new Children();

                if ($form->submitted('addStep1')) {
                    if ($form->validate()) {
                        if ($form['children']->model->save(false)) {
                            $childRelation              = new ChildrenRelation();
                            $childRelation->user_id     = Yii::app()->user->id;
                            $childRelation->children_id = $form['children']->model->id;
                            $childRelation->save();
                            $this->redirect(array('children/add/step2', 'children_id' => $form['children']->model->id));
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
                $form = new CForm('application.views.children.add' . ucfirst($step) . 'Form');

                $form['children']->model = new ChildrenPhoto();
                $childId = $_GET['children_id'];

                $this->render(
                    'add' . ucfirst($step), array(
                        'form' => $form,
                    )
                );

                if ($form->submitted('addStep2') && $childId) {
                    $images = CUploadedFile::getInstances($form['children']->model, 'image');

                    if (!empty($images)) {
                        $savePath = Yii::getPathOfAlias('webroot') . "/children/{$childId}/photos/";
                        if (!is_dir($savePath)) mkdir($savePath, 0777, true);
                        foreach ($images as $image => $pic) {

                            if ($pic->saveAs($savePath . $pic->name)) {
                                $photo              = $form['children']->model;
                                $photo->image       = $pic;
                                $photo->filename    = $pic->name;
                                $photo->children_id = $childId;
                                $photo->save();

                                $this->redirect(array('children/add/step3', 'children_id' => $childId));
                            } else {
                                $form['children']->model->addError;
                                
                                echo 'Cannot upload!';
                            }
                        }
                    }
                }

                break;
            case 'step3':
                break;
            case 'step4':
                break;
        }
    }

}