<?php

class salaryDeductionAction extends baseAdminAction {


    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request)
    {

        $usrObj = $this->getUser()->getAttribute( 'user' );
        if (!$usrObj->isAdmin()) {
            try {
                $this->redirect( 'pim/viewPersonalDetails' );
            } catch (sfStopException $e) {
            }
        }

    }

}

