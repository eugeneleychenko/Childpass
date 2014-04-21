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
                            $this->redirect(array('children/add/step2'));
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

                $form['children']->model = new Child();

                $this->render(
                    'add' . ucfirst($step), array(
                        'form' => $form,
                    )
                );

                if ($form->submitted('addStep2')) {
                    $images = CUploadedFile::getInstances($form['children']->model, 'image');
                    $child  = $form['children']->model;

                    if (!empty($images)) {
                        foreach ($images as $image => $pic) {

                            if ($pic->saveAs(Yii::getPathOfAlias('webroot') . "/children/{$child->primaryKey}/photos/" . $pic->name)) {
                                $photo              = new ChildrenPhoto();
                                $photo->filename    = $pic->name;
                                $photo->children_id = $child->id;

                                $photo->save();
                            } else {
                                $form['children']->model->addError
                                    
                                }
                                
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