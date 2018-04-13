<?php

namespace app\models\base\traits;

trait MembershipTrait
{
    
    /**
     * Подтвердить гражданство
     * @param boolean $save
     */
    public function approve($save = true)
    {
        $this->dateApproved = time();
        if ($save) {
            return $this->save();
        }
        return true;
    }

    /**
     * 
     * @param boolean $self
     * @return boolean
     */
    public function fire($self = false)
    {
        return $this->delete();
    }

    /**
     * 
     * @return boolean
     */
    public function fireSelf()
    {
        return $this->fire(true);
    }
    
}
