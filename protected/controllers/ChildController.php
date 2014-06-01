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
            $form['child']->model = Child::model()->with('relatives.childRelative.relation')->findByPk($childId);

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

                //get relatives mapped to child
                if ($childId) {
                    $relatives = array();
                    foreach ($form['child']->model->relatives as $relative) {

                        $childRelation = ChildRelative::model()->with('relation')->find('child_id = :child_id AND relative_id = :relative_id',
                                                                                        array(':child_id' => $childId, ':relative_id' => $relative->id)
                                                                                       );

                        $relatives[] = array(
                            'id' => $relative->primaryKey,
                            'first_name' => $relative->first_name,
                            'last_name' => $relative->last_name,
                            'relation' => $childRelation->relation,
                            'selectedRelation' => array( $childRelation->relation->id ),
                            'childRelationId' => $childRelation->primaryKey
                        );
                    }

                    $data['relatives'] = $relatives;
                    $data['childId'] = $childId;
                } else {
                    $data['relatives'] = array();
                }
                $data['relationOptions'] = Relation::model()->getOptions();

                if ($form->submitted('next_step')) {
                    $form['child']->model->user_id = Yii::app()->user->getId();
                    if ($form->validate()) {
                        if ($form['child']->model->save(false)) {

                            if (!$childId) {
                                $childId = $form['child']->model->primaryKey;
                            }

                            if (isset($_POST['Relative'])) {
                                Relative::model()->saveRelatives($childId, $_POST['Relative']);
                            }

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
                            $filename = $imageHelper->saveImage($childId, $pic->tempname);

                            $photo           = new ChildPhoto();
                            $photo->image    = $pic;
                            $photo->filename = $filename;
                            $photo->child_id = $childId;
                            $photo->save();
                        }
                    }
                    $this->redirect(array('child/add', 'step' => 'step3', 'child_id' => $childId));
                } else {
                    $childPhotos = ChildPhoto::model()->findAll('child_id = :child_id', array(':child_id' => $childId));
                    foreach ($childPhotos as $photo) {
                        $photo->filename = $imageHelper->getChildImageUrl($childId, $photo->filename);
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

    public function actionDeleteRelativeMapping()
    {
        $childId = Yii::app()->request->getDelete('childId');
        $relativeId = Yii::app()->request->getDelete('relativeId');

        if (empty($childId) || is_array($relativeId) || empty($relativeId)) {
            throw new CHttpException('500', 'Incorrect parameters!');
        }

        try {
            $model = ChildRelative::model();
            $result = $model->removeMapping($childId, $relativeId);
            $this->renderJSON($result);

        } catch(Exception $e) {
            throw new CHttpException('500', 'Failed to delete relative!');
        }
    }

    public function actionList()
    {
        $userId = Yii::app()->user->getId();

        $imageHelper = new ImageHelper();

        $childList = Child::model()->with(
            array('childPhotos' => array(
                'select'   => array('filename'),
                'joinType' => 'LEFT JOIN',
                'order'    => 'is_main DESC'
            ),
            )
        )->findAll('user_id = :user_id', array(':user_id' => $userId));

        foreach ($childList as &$child) {
            if (isset($child->childPhotos[0])) {
                foreach ($child->childPhotos as $key => $photo) {
                    if ($key == 0) {
                        $photo->filename = $imageHelper->getChildImageUrl($child->id, $photo->filename);
                    }
                }
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