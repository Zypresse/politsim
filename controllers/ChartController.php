<?php

namespace app\controllers;

use app\controllers\base\MyController,
    app\models\politics\Party,
    app\models\politics\State,
    app\models\User,
    app\models\economics\Company;

/**
 * Рейтинги
 *
 * @author dev
 */
final class ChartController extends MyController
{
    
    public function actionStates()
    {
        $list = State::find()
                ->orderBy(['population' => SORT_DESC])
                ->where(['dateDeleted' => null])
                ->all();
        return $this->render('states', [
            'list' => $list
        ]);
    }
    
    public function actionParties()
    {
        $list = Party::find()
                ->orderBy(['fame' => SORT_DESC])
                ->with('state')
                ->where(['dateDeleted' => null])
                ->andWhere(['is not', 'dateConfirmed', null])
                ->all();
        return $this->render('parties', [
            'list' => $list
        ]);
    }
    
    public function actionUsers()
    {
        $list = User::find()
                ->orderBy(['fame' => SORT_DESC])
                ->all();
        return $this->render('users', [
            'list' => $list
        ]);
    }
    
    public function actionCompanies()
    {
        $list = Company::find()
                ->orderBy(['capitalization' => SORT_DESC])
                ->where(['dateDeleted' => null])
                ->with('state')
                ->all();
        return $this->render('companies', [
            'list' => $list
        ]);
    }
    
}
