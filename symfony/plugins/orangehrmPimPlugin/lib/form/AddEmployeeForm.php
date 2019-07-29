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
 */
class AddEmployeeForm extends sfForm {

    private $employeeService;
    private $userService;
    private $widgets = array();
    public $createUserAccount = 0;
    protected $openIdEnabled  = false;
    const ESS_USER_ROLE_TYPE = 2;

    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    private function getUserService() {

        if (is_null($this->userService)) {
            $this->userService = new SystemUserService();
        }

        return $this->userService;
    }
    public function getNationalityService() {
        if (is_null($this->nationalityService)) {
            $this->nationalityService = new NationalityService();
        }
        return $this->nationalityService;
    }

    /**
     * Set NationalityService
     * @param NationalityService $nationalityService
     */
    public function setNationalityService(NationalityService $nationalityService) {
        $this->nationalityService = $nationalityService;
    }

    private function getNationalityList() {
        $nationalityService = $this->getNationalityService();
        $nationalities = $nationalityService->getNationalityList();
        $list = array(0 => "-- " . __('Select') . " --");

        foreach ($nationalities as $nationality) {
            $list[$nationality->getId()] = $nationality->getName();
        }
        return $list;
    }


    function connect()
    {
        define( "DB_NAME", "orangehrm" );
        define( "DB_HOST", "localhost" );
        define( "DB_USERNAME", "root" );
        define( "DB_PASSWORD", "" );
        $connection = mysqli_connect( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME );
        if (!$connection) {
            die( 'Could not connect database' );
        }
        return $connection;
    }
    private function getAllowanceList() {
        $connection = $this->connect();
        $allowanceList = array();
        $query = "SELECT allowance,id FROM hs_hr_emp_allowances WHERE status = 1 ";
        $result = mysqli_query($connection,$query) or die('could not select: '.mysqli_error($connection));
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $allowanceList[$row['id']] = $row['allowance'];
        }
        return $allowanceList;
    }
    private function getDeductionList() {
        $connection = $this->connect();
        $deductionList = array();
        $query = "SELECT deduction_name,id FROM hs_hr_emp_deductions WHERE status = 1 ";
        $result = mysqli_query($connection,$query) or die('could not select: '.mysqli_error($connection));
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $deductionList[$row['id']] = $row['deduction_name'];

        }
        return $deductionList;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    public function configure() {

        $status = array('Enabled' => __('Enabled'), 'Disabled' => __('Disabled'));
        
        if($this->getOption('openIdEnabled') == 'on'){
            $this->openIdEnabled = true;
        }


        $idGenService = new IDGeneratorService();
        $idGenService->setEntity(new Employee());
        $empNumber = $idGenService->getNextID(false);
        $employeeId = str_pad($empNumber, 4, '0');

        $this->widgets = array(
            'firstName' => new sfWidgetFormInputText(array(), array("class" => "form-control", "maxlength" => 30)),
            'middleName' => new sfWidgetFormInputText(array(), array("class" => "form-control", "maxlength" => 30)),
            'lastName' => new sfWidgetFormInputText(array(), array("class" => "form-control", "maxlength" => 30)),
            'fatherName' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),
            'motherName' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),
            'optGender' => new sfWidgetFormChoice(array('choices' => array(0=> __('-- Select --'),1 => __("Male"), 2 => __("Female")))),
            'cmbNation' => new sfWidgetFormSelect(array('choices' => $this->getNationalityList())),
            'cmbAllowance[]' => new sfWidgetFormSelect(array('choices' => $this->getAllowanceList()),array('class'=>'example-selectAllNumber','multiple'=>'multiple')),
            'cmbDeduction[]' => new sfWidgetFormSelect(array('choices' => $this->getDeductionList()),array('class'=>'example-selectAllNumber','multiple'=>'multiple')),
            'chkOther' => new sfWidgetFormInputCheckbox(array('value_attribute_value' => 1), array()),
            'otherName' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),
            'otherAmount' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),

            'ntn' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),
            'otherId' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),
            'DOB' => new ohrmWidgetDatePicker(array(), array('id' => 'personal_DOB')),
            'address' => new sfWidgetFormInputText(array(),array("class" => "form-control", "maxlength" => 30)),
            'employeeId' => new sfWidgetFormInputText(array(), array("class" => "form-control", "maxlength" => 10)),
            'photofile' => new sfWidgetFormInputFileEditable(array('edit_mode' => false, 'with_delete' => false, 
                'file_src' => ''), array("class" => "form-control-file")),
            'chkLogin' => new sfWidgetFormInputCheckbox(array('value_attribute_value' => 1), array()),
            'user_name' => new sfWidgetFormInputText(array(), array("class" => "form-control", "maxlength" => 40)),
            'user_password' => new ohrmWidgetFormInputPassword(array(), array("class" => "form-control passwordRequired",
                "maxlength" => 64, "autocomplete" => "off")),
            're_password' => new sfWidgetFormInputPassword(array(), array("class" => "form-control passwordRequired",
                "maxlength" => 64, "autocomplete" => "off")),
            'status' => new sfWidgetFormSelect(array('choices' => $status), array("class" => "form-control")),
            'empNumber' => new sfWidgetFormInputHidden(),

        );

        $this->widgets['empNumber']->setDefault($empNumber);
        $this->widgets['employeeId']->setDefault($employeeId);

        if ($this->getOption(('employeeId')) != "") {
            $this->widgets['employeeId']->setDefault($this->getOption(('employeeId')));
        }

        $this->widgets['firstName']->setDefault($this->getOption('firstName'));
        $this->widgets['middleName']->setDefault($this->getOption('middleName'));
        $this->widgets['lastName']->setDefault($this->getOption('lastName'));
        $this->widgets['optGender']->setDefault($this->gender);
        $this->widgets['chkLogin']->setDefault($this->getOption('chkLogin'));
        $this->widgets['chkOther']->setDefault($this->getOption('chkOther'));
        $this->widgets['user_name']->setDefault($this->getOption('user_name'));
        $this->widgets['user_password']->setDefault($this->getOption('user_password'));
        $this->widgets['re_password']->setDefault($this->getOption('re_password'));
        
        $selectedStatus = $this->getOption('status');
        if (empty($selectedStatus) || !isset($status[$selectedStatus])) {
            $selectedStatus = 'Enabled';
        }
        $this->widgets['status']->setDefault($selectedStatus);

        $this->setWidgets($this->widgets);
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $this->setValidators(array(
            'photofile' => new sfValidatorFile(array('max_size' => 1000000, 'required' => false)),
            'firstName' => new sfValidatorString(array('required' => true, 'max_length' => 30, 'trim' => true)),
            'empNumber' => new sfValidatorString(array('required' => false)),
            'lastName' => new sfValidatorString(array('required' => true, 'max_length' => 30, 'trim' => true)),
            'middleName' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true)),
            'employeeId' => new sfValidatorString(array('required' => false, 'max_length' => 10)),
            'chkLogin' => new sfValidatorString(array('required' => false)),
            'chkOther' => new sfValidatorString(array('required' => false)),
            'user_name' => new sfValidatorString(array('required' => false, 'max_length' => 40, 'trim' => true)),
            'otherName' => new sfValidatorString(array('required' => false, 'max_length' => 60, 'trim' => true)),
            'otherAmount' => new sfValidatorString(array('required' => false, 'max_length' => 60, 'trim' => true)),
            'user_password' => new ohrmValidatorPassword(array('required' => false, 'max_length' => 64, 'trim' => true)),
            're_password' => new sfValidatorPassword(array('required' => false,'min_length' => 8, 'max_length' => 64, 'trim' => true)),
            'status' => new sfValidatorString(array('required' => false)),
            'optGender' => new sfValidatorChoice(array('required' => false,
                'choices' => array(Employee::GENDER_MALE, Employee::GENDER_FEMALE),
                'multiple' => false)),
            'cmbNation' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getNationalityList()))),
            'cmbAllowance' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAllowanceList()),'multiple'=>'multiple')),
            'cmbDeduction' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDeductionList()),'multiple'=>'multiple')),
            'fatherName' => new sfValidatorString(array('required'=>false)),
            'motherName' => new sfValidatorString(array('required'=>false)),
            'ntn' => new sfValidatorString(array('required'=>false)),
            'address' => new sfValidatorString(array('required'=>false)),
            'otherId' => new sfValidatorString(array('required'=>false)),
            'DOB' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false), array('invalid' => "Date format should be" . $inputDatePattern))

    ));

        $this->getWidgetSchema()->setLabels($this->getFormLabels());

        $formExtension = PluginFormMergeManager::instance();
        $formExtension->mergeForms($this, 'addEmployee', 'AddEmployeeForm');
        
        
        $customRowFormats[0] = " <div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">". __('Full Name') . "</label> <div class=\"col-sm-10\"><div class=\"col-sm-3\"><div class=\"fieldDescription\"><em>*</em> ". __('First Name') . "</div>\n %field%%help%\n%hidden_fields%%error%</div>\n";
        $customRowFormats[1] = "<div class=\"col-sm-3\"><div class=\"fieldDescription\">". __('Middle Name') . "</div>\n %field%%help%\n%hidden_fields%%error%</div>\n";
        $customRowFormats[2] = "<div class=\"col-sm-3\"><div class=\"fieldDescription\"><em>*</em> ". __('Last Name') . "</div>\n %field%%help%\n%hidden_fields%%error%</div>\n</div>\n</div>\n";
        $customRowFormats[3] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[4] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[5] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[6] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[7] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[8] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[9] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[10] = "<div class=\"form-group otherSection\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[11] = "<div class=\"form-group otherSection\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[12] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[13] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[14] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-6\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[15] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[16] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[17] = "<div class=\"form-group\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[18] = "<div class=\"form-group\"> <label class=\"control-label col-sm-3\" for=\"email\">%label%</label><div class=\"col-sm-6\">\n %field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[19] = "<div class=\"form-group loginSection\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n%field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[20] = "<div class=\"form-group loginSection\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n%field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[21] = "<div class=\"form-group loginSection\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n%field%%help%\n%hidden_fields%%error%</div></div>";
        $customRowFormats[22] = "<div class=\"form-group loginSection\"> <label class=\"control-label col-sm-2\" for=\"email\">%label%</label><div class=\"col-sm-3\">\n%field%%help%\n%hidden_fields%%error%</div></div>";

        
        sfWidgetFormSchemaFormatterCustomRowFormat::setCustomRowFormats($customRowFormats);
        $this->widgetSchema->setFormFormatterName('CustomRowFormat');
        
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array(
            'photofile' => __('Photograph'),
            'fullNameLabel' => __('Full Name'),
            'firstName' => false,
            'middleName' => false,
            'lastName' => false,
            'fatherName' => __("Father's Name"),
            'motherName' => __("Mother's Name"),
            'cmbDeduction[]' => __('Deduction'),
            'cmbAllowance[]' => __('Allowance'),
            'otherName' => __('Other Allowance Name'). '<em> *</em>',
            'otherAmount' => __('Other Allowance Amount').'<em> *</em>',
            'ntn' => __('NTN'),
            'DOB' => __('Date of Birth'),
            'optGender' => __('Gender'),
            'cmbNation' => __('Nationality'),
            'otherId' => __('CNIC/Passport'),
            'employeeId' => __('Employee Id'),
            'chkLogin' => __('Create Login Details'),
            'chkOther' => __('Other Allowance'),
            'user_name' => __('User Name') . '<em> *</em>',
            'user_password' => __('Password') . '<em id="password_required"> *</em>',
            're_password' => __('Confirm Password') . '<em id="rePassword_required"> *</em>',
            'status' => __('Status') . '<em> *</em>'
        );

        return $labels;
    }
    
    public function getEmployee(){
        $posts = $this->getValues();
        $employee = new Employee();
        $employee->firstName = $posts['firstName'];
        $employee->lastName = $posts['lastName'];
        $employee->middleName = $posts['middleName'];
        $employee->fatherName = $posts['fatherName'];
        $employee->motherName = $posts['motherName'];
        $employee->ntn = $posts['ntn'];
        $employee->allowance = implode(',',$posts['cmbAllowance']);
        $employee->deduction = implode(',',$posts['cmbDeduction']);
        $employee->otherName = $posts['otherName'];
        $employee->otherAmount = $posts['otherAmount'];
        $employee->emp_birthday = $posts['DOB'];
        $employee->street1 = $posts['address'];
        $employee->emp_gender = $posts['optGender'];
        $employee->nation_code = $posts['cmbNation'];
        $employee->custom1 = $posts['designation'];
        $employee->custom2 = $posts['department'];
        $employee->otherId = $posts['otherId'];
        $employee->employeeId = $posts['employeeId'];
        return $employee;
    }

    public function save() {

        $posts = $this->getValues();
        $file = $posts['photofile'];
        $employee = $this->getEmployee();

        $employeeService = $this->getEmployeeService();
        $employeeService->saveEmployee($employee);

        $empNumber = $employee->empNumber;

        //saving emp picture
        if (($file instanceof sfValidatedFile) && $file->getOriginalName() != "") {
            $empPicture = new EmpPicture();
            $empPicture->emp_number = $empNumber;
            $tempName = $file->getTempName();

            $empPicture->picture = file_get_contents($tempName);
            ;
            $empPicture->filename = $file->getOriginalName();
            $empPicture->file_type = $file->getType();
            $empPicture->size = $file->getSize();
            list($width, $height) = getimagesize($file->getTempName());
            $sizeArray = $this->pictureSizeAdjust($height, $width);
            $empPicture->width = $sizeArray['width'];
            $empPicture->height = $sizeArray['height'];
            $empPicture->save();
        }

        if ($this->createUserAccount) {
            $this->saveUser($empNumber);
        }

        //merge location dropdown
        $formExtension = PluginFormMergeManager::instance();
        $formExtension->saveMergeForms($this, 'addEmployee', 'AddEmployeeForm');

        return $empNumber;
    }

    private function saveUser($empNumber) {

        $posts = $this->getValues();

        $sfUser = sfContext::getInstance()->getUser();

        $password           = $posts['user_password'];
        $confirmedPassword  = $posts['re_password'];
        $isPasswordEmpty             = (empty($password) && empty($confirmedPassword))?true:false;
        $hasLdapAvailable             = $sfUser->getAttribute('ldap.available');

        if (trim($posts['user_name']) != "") {
            $userService = $this->getUserService();

            if (!$hasLdapAvailable && ((trim($posts['user_password']) != "" && $posts['user_password'] == $posts['re_password']) || (trim($posts['user_password']) == "" && $this->openIdEnabled))) {
                $user = new SystemUser();
                $user->setDateEntered(date('Y-m-d H:i:s'));
                $user->setCreatedBy(sfContext::getInstance()->getUser()->getAttribute('user')->getUserId());
                $user->user_name = $posts['user_name'];
                $user->user_password = $posts['user_password'];
                $user->emp_number = $empNumber;
                $user->setStatus(($posts['status'] == 'Enabled') ? '1' : '0');
                $user->setUserRoleId(2);
                $userService->saveSystemUser($user, true);
            }

            if($isPasswordEmpty && $hasLdapAvailable){
                $this->_handleLdapEnabledUser($posts, $empNumber);
            }
        }
    }

    private function pictureSizeAdjust($imgHeight, $imgWidth) {

        if ($imgHeight > 200 || $imgWidth > 200) {
            $newHeight = 0;
            $newWidth = 0;

            $propHeight = floor(($imgHeight / $imgWidth) * 200);
            $propWidth = floor(($imgWidth / $imgHeight) * 200);

            if ($propHeight <= 200) {
                $newHeight = $propHeight;
                $newWidth = 200;
            }

            if ($propWidth <= 200) {
                $newWidth = $propWidth;
                $newHeight = 200;
            }
        } else {
            if ($imgHeight <= 200)
                $newHeight = $imgHeight;

            if ($imgWidth <= 200)
                $newWidth = $imgWidth;
        }
        return array('width' => $newWidth, 'height' => $newHeight);
    }

    protected function _handleLdapEnabledUser($postedValues, $empNumber)
    {

        $sfUser = sfContext::getInstance()->getUser();
        $user = new SystemUser();
        $user->setDateEntered(date('Y-m-d H:i:s'));
        $user->setCreatedBy($sfUser->getAttribute('user')->getUserId());
        $user->user_name = $postedValues['user_name'];
        $user->user_password = '';
        $user->emp_number = $empNumber;
        $user->setUserRoleId(self::ESS_USER_ROLE_TYPE);
        $this->getUserService()->saveSystemUser($user, true);

    }    
}