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
        switch($step) {
            case 'step1':
                $form = new CForm('application.views.children.add'.ucfirst($step).'Form');

                $form['children']->model = new Children();

                if ($form->submitted('addStep1')) {
                    if ($form->validate()) {
                        if ($form['children']->model->save(false)) {
                            $childRelation = new ChildrenRelation();
                            $childRelation->user_id = Yii::app()->user->id;
                            $childRelation->children_id = $form['children']->model->id;
                            $childRelation->save();
                            $this->redirect(array('children/add/step2'));
                        }
                    }
                }
                $this->render(
                    'add'.ucfirst($step), array(
                        'form' => $form,
                    )
                );
                break;
            case 'step2':
                $form = new CForm('application.views.children.add'.ucfirst($step).'Form');

                $form['children']->model = new ChildrenPhoto();

                $this->render(
                    'add'.ucfirst($step), array(
                        'form' => $form,
                    )
                );
                break;
            case 'step3':
                break;
            case 'step4':
                break;
        }
    }

}