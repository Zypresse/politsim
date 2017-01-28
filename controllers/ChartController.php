<?php

namespace app\controllers;

use app\components\MyController,
    app\models\politics\Party,
    app\models\politics\State,
    app\models\User,
    app\models\economics\Company;

/**
 * Рейтинги
 *
 * @author dev
 */
class ChartController extends MyController
{
    
    public function actionStates()
    {
        $list = State::find()->orderBy(['population' => SORT_DESC])->all();
        return $this->render('states', [
            'list' => $list
        ]);
    }
    
    public function actionParties()
    {
        $list = Party::find()->orderBy(['fame' => SORT_DESC])->with('state')->all();
        return $this->render('parties', [
            'list' => $list
        ]);
    }
    
    public function actionUsers()
    {
        $list = User::find()->orderBy(['fame' => SORT_DESC])->all();
        return $this->render('users', [
            'list' => $list
        ]);
    }
    
    public function actionCompanies()
    {
        $list = Company::find()->orderBy(['capitalization' => SORT_DESC])->all();
        return $this->render('companies', [
            'list' => $list
        ]);
    }
    
}
