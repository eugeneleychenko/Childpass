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

            if (!$form['child']->model ||  !$form['child']->model->checkAccess()) {
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

                        $childRelation = ChildRelative::model()->with('relation')->find(
                            'child_id = :child_id AND relative_id = :relative_id',
                            array(
                                ':child_id'    => $childId,
                                ':relative_id' => $relative->id
                            )
                        );

                        $relatives[] = array(
                            'id'               => $relative->primaryKey,
                            'first_name'       => $relative->first_name,
                            'last_name'        => $relative->last_name,
                            'relation'         => $childRelation->relation,
                            'selectedRelation' => array($childRelation->relation->id),
                            'childRelationId'  => $childRelation->primaryKey
                        );
                    }

                    $data['relatives'] = $relatives;
                    $data['childId']   = $childId;
                } else {
                    $data['relatives'] = array();
                }
                $data['isAddingNextChild'] = ChildRelative::model()->userHasChildRelativeMapping(Yii::app()->user->getId());

                $data['relationOptions'] = Relation::model()->getOptions();

                if ($form->submitted('next_step')) {

                    $form['child']->model->user_id = Yii::app()->user->getId();

                    $transaction = $form['child']->model->dbConnection->beginTransaction();

                    if ($form->validate()) {
                        if ($form['child']->model->save(false)) {

                            if (!$childId) {
                                $childId = $form['child']->model->primaryKey;
                            }

                            if (isset($_POST['Relative'])) {
                                Relative::model()->saveRelatives($childId, $_POST['Relative'], $form['child']->model->user_id);
                            }

                            //$childRelativesNumber = count(ChildRelative::model()->childRelativesMapping($childId));
                            //if (!$childRelativesNumber) {
                            //    $transaction->rollback();
                            //    $this->redirect(array('child/list'));
                            //} else {
                            $transaction->commit();
                            //}

                            $this->redirect(array('child/add', 'step' => 'step2', 'child_id' => $form['child']->model->id));
                        }
                    } else {
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
                        $this->redirect(array('child/add', 'step' => 'step4', 'child_id' => $childId));
                    }
                } elseif ($form->submitted('prev_step')) {
                    $this->redirect(array('child/add', 'step' => 'step2', 'child_id' => $childId));
                }
                break;
            case 'step4':
                $form['child']->model = User::model()->findByPk(Yii::app()->user->getId());
                if ($form->submitted('next_step')) {
                    if ($form->validate()) {
                        if ($form['child']->model->save(false)) {
                            $this->redirect(array('child/list'));
                        }
                    }
                } elseif ($form->submitted('prev_step')) {
                    $this->redirect(array('child/add', 'step' => 'step3', 'child_id' => $childId));
                }
                break;
        }

        $data['step'] = $step;

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


    function actionGetSavedRelatives()
    {
        $userId = Yii::app()->user->getId();
        $relatives = array();
        $childRelatives = ChildRelative::model()->with(array(
            'relative',
            'child' => array(
                'alias'     => 'child',
                'joinType'  => 'INNER JOIN',
                'condition' => 'child.user_id = :user_id',
                'params'    => array(':user_id' => $userId)
            )
        ))->findAll();

        foreach ($childRelatives as $childRelative) {
            if (!array_key_exists($childRelative->relative_id, $relatives)) {
                $relatives[$childRelative->relative_id] = array(
                    'relative_id' =>  $childRelative->relative_id,
                    'first_name' => $childRelative->relative->first_name,
                    'last_name' => $childRelative->relative->last_name,
                    'relation_id' => $childRelative->relation_id
                );
            }
        }

        $this->renderJSON(array_values($relatives));
    }


    public function actionActivateAlert()
    {
        $this->layout = 'ajax';
        $userId = Yii::app()->user->getId();
        $userChildren = Child::model()->findAll('user_id = :user_id AND NOT EXISTS (SELECT incident.id FROM incident WHERE incident.child_id = t.id)',
                                                array(':user_id' => $userId));

        if (!count($userChildren)) {
            $noChildren = true;
            $this->render(
                'activateAlert',
                array(
                      'noChildren' => $noChildren
                )
            );
            exit;
        }

        $noChildren = false;

        $incidentModelClass = 'Incident';

        $childrenInfo = array();
        $userChildIds = array();
        foreach ($userChildren as $child) {
            $incidentModel = new $incidentModelClass;
            $userChildIds[] = $child->primaryKey;
            $incidentModel->child_id = $child->primaryKey;
            $incidentModel->child_description = $child->distinctive_marks;
            $childrenInfo[] = array(
                'child' => $child,
                'incidentModel' => $incidentModel,
            );
        }

        $descriptionValue = '';
        $dateValue = '';
        if ( isset($_POST[$incidentModelClass]) && is_array($_POST[$incidentModelClass]) && count($_POST[$incidentModelClass]) ) {
            foreach ($_POST[$incidentModelClass] as $number => $incident) {

                //to ignore children of other users
                if (!in_array($incident['child_id'], $userChildIds)) {
                    continue;
                }

                $errorsExist = false;

                $attributes = $incident + array(
                        'description' => $_POST['description'],
                        'date' => $_POST['date']
                    );
                $childrenInfo[$number]['incidentModel']->attributes = $attributes;
                if (!$childrenInfo[$number]['incidentModel']->save()) {
                    $descriptionValue = $childrenInfo[$number]['incidentModel']->description;
                    $dateValue = $childrenInfo[$number]['incidentModel']->date;
                    $errorsExist = true;
                }
            }
        }

        if (!isset($errorsExist)) {
            $errorsExist = false;
            $saved = false;
        } else {
            $saved = !$errorsExist;
        }


        $this->render(
            'activateAlert',
            array(
                'noChildren' => $noChildren,
                'childrenInfo' => $childrenInfo,
                'saved' => $saved,
                'errorsExist' => $errorsExist,
                'descriptionValue' => $descriptionValue,
                'dateValue' => $dateValue
                )
        );
    }

    public function actionList()
    {
        $userId = Yii::app()->user->getId();

        $childList = Child::model()->with(array(
            'childPhotos' => array(
                'select'   => array('filename', 'child_id'),
                'joinType' => 'LEFT JOIN',
                'order'    => 'is_main DESC'
            ),
            'incident'
        ))->findAll('user_id = :user_id', array(':user_id' => $userId));

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
        if (!$missingInfo) {
            throw new CHttpException('404', 'Child does not exist!');
        }

        if (Yii::app()->user->getId() != $missingInfo['user_id']) {
            throw new CHttpException('403', 'Access forbidden!');
        };

        $this->render(
            'generateFlyer', array(
                'missingInfo'   => $missingInfo,
            )
        );
    }

    public function actionDownloadFlyer($id)
    {
        $missingInfo = Child::model()->getMissingInfo($id);
        if (!$missingInfo) {
            throw new CHttpException('404', 'Child does not exist!');
        }

        if (Yii::app()->user->getId() != $missingInfo['user_id']) {
            throw new CHttpException('403', 'Access forbidden!');
        };

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
        $fileName = $missingInfo['child']->first_name . ' is missing';
        $mPDF1->Output($fileName, 'D');
    }

    public function actionSurvey()
    {
        $this->layout = 'main';
        $model = new SurveyForm();

        if(isset($_POST['SurveyForm'])) {
            $model->attributes = $_POST['SurveyForm'];
            if ($model->validate()) {

                $attributesLabels = $model->attributeLabels();
                $surveyResults = array();
                foreach ($model->attributes as $attribute => $value) {
                    $surveyResults[] = array('question' => $attributesLabels[$attribute], 'answer' => $value);
                }

                Yii::app()->common->sendEmail(
                    Yii::app()->params['surveyEmail'],
                    'Survey results of user ' . Yii::app()->user->getName(),
                    'survey_results',
                    array(
                        'username' => Yii::app()->user->getName(),
                        'surveyResults' => $surveyResults
                    )
                );

                $this->redirect(array('child/list'));
            }
        }

        $this->render(
            'survey', array(
                'model' => $model
            )
        );

    }
}