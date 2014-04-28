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
                'actions' => array('add, list'),
                'users' => array('*'),
            ),
            array('deny'),
        );
    }

    public function actionAdd($step = 'step1')
    {
        $form = new CForm('application.views.child.add' . ucfirst($step) . 'Form');

        $childId = isset($_GET['child_id']) ? $_GET['child_id'] : false;

        if ($childId) {
            $form['child']->model = Child::model()->findByPk($childId);
            if (!$form['child']->model->checkAccess()) {
                $this->redirect(array('child/list'));
            }
        } else {
            $form['child']->model = new Child();
        }

        $data = array();

        switch ($step) {
            case 'step1':
                $form['child']->model->birthday = $form['child']->model->getBirthday();

                if ($form->submitted('next_step')) {
                    $form['child']->model->user_id = Yii::app()->user->getId();
                    if ($form->validate()) {
                        if ($form['child']->model->save(false)) {
                            $this->redirect(array('child/add', 'step' => 'step2', 'child_id' => $form['child']->model->id));
                        }
                    }
                }

                break;
            case 'step2':
                $form['child']->model = new ChildPhoto();

                $childId = $_GET['child_id'];

                $imageHelper = new ImageHelper();

                if ($form->submitted('next_step') && $childId) {
                    $images = CUploadedFile::getInstances($form['child']->model, 'image');

                    if (!empty($images)) {
                        foreach ($images as $pic) {
                            $photo           = new ChildPhoto();
                            $photo->filename = $imageHelper->generateImageName();
                            $photo->image    = $pic;
                            $photo->tempName = $pic->tempname;
                            $photo->child_id = $childId;
                            $photo->save();
                        }
                    }
                    $this->redirect(array('child/add', 'step' => 'step3', 'child_id' => $childId));
                } else {
                    $childPhotos = ChildPhoto::model()->findAll('child_id = :child_id', array(':child_id' => $childId));
                    foreach ($childPhotos as $photo) {
                        $photo->filename = $photo->getUrl(ImageHelper::IMAGE_SMALL);
                    }
                    $data['childPhotos'] = $childPhotos;
                }

                if ($form->submitted('prev_step')) {
                    $this->redirect(array('child/add', 'step' => 'step1', 'child_id' => $childId));
                }

                break;
            case 'step3':
                if ($form->submitted('next_step')) {
                    $form['child']->model->teeth = $_POST['Child']['teeth'];
                    if ($form['child']->model->save(false)) {
                        $this->redirect(array('child/list'));
                    }
                } elseif ($form->submitted('prev_step')) {
                    $this->redirect(array('child/add', 'step' => 'step2', 'child_id' => $childId));
                }
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
                'select'   => array('filename', 'child_id'),
                'joinType' => 'LEFT JOIN',
                'order'    => 'is_main DESC'
            ),
            )
        )->findAll('user_id = :user_id', array(':user_id' => $userId));

        foreach ($childList as &$child) {
            if (isset($child->childPhotos[0])) {
                $photos = $child->childPhotos;
                $photos[0]->filename = $photos[0]->getUrl(ImageHelper::IMAGE_SMALL);
            }
        }

        $this->render(
            'childList', array(
                'childList' => $childList,
            )
        );
    }

    public function actionGenerateFlyer($id)
    {
        $missingInfo = Child::model()->getMissingInfo($id);

        $this->render(
            'generateFlyer', array(
                'missingInfo'   => $missingInfo,
            )
        );

    }

    public function actionDownloadFlyer($id)
    {
        $missingInfo = Child::model()->getMissingInfo($id);

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'Letter-L');

        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/app.css');
        $mPDF1->WriteHTML($stylesheet, 1);

        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML(
            $this->renderPartial('generateFlyer', array('missingInfo' => $missingInfo,), true)
        );

        # Outputs ready PDF
        $fileName = $missingInfo['child']->name . ' is missing';
        $mPDF1->Output($fileName, 'D');
    }

}