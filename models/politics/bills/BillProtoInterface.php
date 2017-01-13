<?php

namespace app\models\politics\bills;

use app\models\politics\bills\Bill;

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
            
}
