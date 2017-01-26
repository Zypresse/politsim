<?php

namespace app\models\politics\bills;

use app\models\politics\bills\Bill,
    app\models\politics\State;

/**
 *
 */
interface BillProtoInterface
{
    
    /**
     * 
     * @param Bill $bill
     */
    public function validate($bill) : bool;
    
    
    /**
     * 
     * @param Bill $bill
     */
    public function accept($bill) : bool;
    
    /**
     * 
     * @param Bill $bill
     */
    public function render($bill) : string;
    
    /**
     * 
     * @param Bill $bill
     */
    public function renderFull($bill) : string;
    
    /**
     * данные по умолчанию для заполнения dataArray
     * @param Bill $bill
     */
    public function getDefaultData($bill);
    
    public function isAvailable(State $state) : bool;
            
}
