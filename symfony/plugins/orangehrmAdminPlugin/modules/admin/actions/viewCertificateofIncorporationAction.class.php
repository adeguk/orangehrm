<?php


class viewCertificateofIncorporationAction extends baseAdminAction {

	public function getLocationService() {
		if (is_null($this->locationService)) {
			$this->locationService = new LocationService();
			$this->locationService->setLocationDao(new LocationDao());
		}
		return $this->locationService;
	}

	public function setForm(sfForm $form) {
		if (is_null($this->form)) {
			$this->form = $form;
		}
	}

	/**
	 *
	 * @param <type> $request
	 */
	public function execute($request) {

		$usrObj = $this->getUser()->getAttribute('user');
		if (!($usrObj->isAdmin())) {
			$this->redirect('pim/viewPersonalDetails');
		}

		$isPaging = $request->getParameter('pageNo');
		$sortField = $request->getParameter('sortField');
		$sortOrder = $request->getParameter('sortOrder');
		$locationId = $request->getParameter('locationId');

		$this->setForm(new SearchLocationForm());

		$pageNumber = $isPaging;
		if ($locationId > 0 && $this->getUser()->hasAttribute('pageNumber')) {
			$pageNumber = $this->getUser()->getAttribute('pageNumber');
		}
		$limit = Location::NO_OF_RECORDS_PER_PAGE;
		$offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $limit) : ($request->getParameter('pageNo', 1) - 1) * $limit;
		$searchClues = $this->_setSearchClues($sortField, $sortOrder, $offset, $limit);

		if (!empty($sortField) && !empty($sortOrder) || $isPaging > 0 || $locationId > 0) {
			if ($this->getUser()->hasAttribute('searchClues')) {
				$searchClues = $this->getUser()->getAttribute('searchClues');
				$searchClues['offset'] = $offset;
				$searchClues['sortField'] = $sortField;
				$searchClues['sortOrder'] = $sortOrder;
				$this->form->setDefaultDataToWidgets($searchClues);
			}
		} else {
			$this->getUser()->setAttribute('searchClues', $searchClues);
		}

		$locationList = $this->getLocationService()->searchLocations($searchClues);
		$locationListCount = $this->getLocationService()->getSearchLocationListCount($searchClues);
		$this->_setListComponent($locationList, $limit, $pageNumber, $locationListCount);
		$this->getUser()->setAttribute('pageNumber', $pageNumber);
		$params = array();
		$this->parmetersForListCompoment = $params;

		if ($request->isMethod('post')) {
			$offset = 0;
			$pageNumber = 1;
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$searchClues = $this->_setSearchClues($sortField, $sortOrder, $offset, $limit);
				$this->getUser()->setAttribute('searchClues', $searchClues);
				$searchedLocationList = $this->getLocationService()->searchLocations($searchClues);
				$locationListCount = $this->getLocationService()->getSearchLocationListCount($searchClues);
				$this->_setListComponent($searchedLocationList, $limit, $pageNumber, $locationListCount);
			} else {
				$this->handleBadRequest();
			}
		}
	}

	/**
	 *
	 * @param type $locationList
	 * @param type $limit
	 * @param type $pageNumber
	 * @param type $recordCount
	 */
	private function _setListComponent($locationList, $limit, $pageNumber, $recordCount) {

		$configurationFactory = new LocationHeaderFactory();
		ohrmListComponent::setPageNumber($pageNumber);
		ohrmListComponent::setConfigurationFactory($configurationFactory);
		ohrmListComponent::setListData($locationList);
		ohrmListComponent::setItemsPerPage($limit);
		ohrmListComponent::setNumberOfRecords($recordCount);
	}

	private function _setSearchClues($sortField, $sortOrder, $offset, $limit) {
		return array(
		    'name' => $this->form->getValue('name'),
		    'city' => $this->form->getValue('city'),
		    'country' => $this->form->getValue('country'),
		    'sortField' => $sortField,
		    'sortOrder' => $sortOrder,
		    'offset' => $offset,
		    'limit' => $limit
		);
	}

}

?>
