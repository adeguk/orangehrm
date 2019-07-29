<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */

class DepartmentForm extends BaseForm {

    private $departmentService;

    public function getDepartmentService() {
        if (is_null($this->departmentService)) {
            $this->departmentService = new DepartmentService();
            $this->departmentService->setDepartmentDao(new DepartmentDao());
        }
        return $this->departmentService;
    }

    public function configure() {

        $this->setWidgets(array(
            'departmentId' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'departmentId' => new sfValidatorNumber(array('required' => false)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 52, 'trim' => true)),
        ));

        $this->widgetSchema->setNameFormat('department[%s]');

    }

    public function save(){

        $departmentId = $this->getValue('departmentId');
        if(!empty ($departmentId)){
            $department = $this->getDepartmentService()->getDepartmentById($departmentId);
        } else {
            $department = new Department();
        }
        $department->setName($this->getValue('name'));
        $department->save();
    }

    public function getDepartmentListAsJson() {

        $list = array();
        $departmentList = $this->getDepartmentService()->getDepartmentList();
        foreach ($departmentList as $department) {
            $list[] = array('id' => $department->getId(), 'name' => $department->getName());
        }
        return json_encode($list);
    }
}

?>
