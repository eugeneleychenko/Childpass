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
        $form = new CForm('application.views.children.add'.ucfirst($step).'Form');

        $form['children']->model = new Children();

        if ($form->submitted('add')) {
            if ($form->validate()) {
                if ($form['children']->model->save(false)) {
                    $this->redirect(array('children/add/step1'));
                }
            }
        }

        $this->render(
            'add'.ucfirst($step), array(
                'form' => $form,
            )
        );
    }

    public function actionSave()
    {
        if (isset($_POST['Children'])) {

        }
    }

}