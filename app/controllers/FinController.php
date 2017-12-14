<?php
/*
 * Created on Feb 27, 2008 Built for web Fuse IQ -- todd@fuseiq.com
 */
require_once ('ReportFilterHelpers.php');
require_once('models/table/Helper.php');
require_once ('models/table/ActivityDetail.php');
require_once ('models/ValidationContainer.php');
require_once ('models/table/Location.php');
require_once ('models/table/OptionList.php');
class FinController extends ReportFilterHelpers {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	public function init() {
	}
	public function preDispatch() {
		parent::preDispatch ();
		
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
			
			// we extend these controllers, lets redirect to their URL
		//if (strstr ( $_SERVER ['HTTP_REFERER'], '/site/' ) && strstr ( $_SERVER ['REQUEST_URI'], '/fin' ))
			//$this->_redirect ( str_replace ( '/fin/', '/site/', '//' . $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'] ) );
	}
	public function indexAction() {
		$this->_redirect ( 'fin/search' );
	}
	public function cityListAction() {
		require_once ('models/table/Location.php');
		
		$rowArray = Location::suggestionQuery ( $this->getParam ( 'query' ), $this->setting ( 'num_location_tiers' ) );
		
		$this->sendData ( $rowArray );
	}
	public function approveAction() {
		if (! $this->hasACL ( 'facility_and_person_approver' ) && ! $this->hasACL ( 'edit_country_options' )) {
			$this->doNoAccessError ();
		}
		
		$id = $this->getSanParam ( 'id' );
		$status = ValidationContainer::instance ();
		$facility = new Facility ();
		$facility_row = $facility->find ( $id )->current ();
		if ($facility_row == null) {
			$status->setStatusMessage ( t ( 'Error approving facility: That record could not be found.' ) );
			$this->_redirect ( 'admin/facilities-new-facilities' );
			return;
		}
		
		$facility_row->approved = 1;
		$facility_row->save ();
		$status->setStatusMessage ( t ( 'That facility has been approved.' ) );
		$this->_redirect ( 'admin/facilities-new-facilities' );
	}
	public function addAction() {

	}
	
	protected function validateAndSave($activitydetailRow, $checkName = true) {
		$status = ValidationContainer::instance ();
		
//		// check for required fields
//		if ($checkName) {
//			$status->checkRequired ( $this, 'facility_name', 'Facility name' );
//			// check for unique
//			if ($this->getParam ( 'facility_name' ) and ! Facility::isUnique ( $this->getParam ( 'facility_name' ), $this->getParam ( 'id' ) )) {
//				$status->addError ( 'facility_name', t ( 'That name already exists.' ) );
//			}
//		}
//		
//		// validate fields
//		if($_SERVER ['SERVER_NAME'] !== 'phc.trainingdata.org'){ //TA: do not make facility type required for Ukraine
//		  $status->checkRequired ( $this, 'facility_type_id', t ( 'Facility type' ) ); //TA:#382
//		}
//		$status->checkRequired ( $this, 'facility_province_id', $this->tr ( 'Region A (Province)' ) );
//		if ($this->setting ( 'display_region_b' ))
//			$status->checkRequired ( $this, 'facility_district_id', $this->tr ( 'Region B (Health District)' ) );
//		if ($this->setting ( 'display_region_c' ))
//			$status->checkRequired ( $this, 'facility_region_c_id', $this->tr ( 'Region C (Local Region)' ) );
//		if ($this->setting ( 'display_region_d' ))
//			$status->checkRequired ( $this, 'facility_region_d_id', $this->tr ( 'Region D' ) );
//		if ($this->setting ( 'display_region_e' ))
//			$status->checkRequired ( $this, 'facility_region_e_id', $this->tr ( 'Region E' ) );
//		if ($this->setting ( 'display_region_f' ))
//			$status->checkRequired ( $this, 'facility_region_f_id', $this->tr ( 'Region F' ) );
//		if ($this->setting ( 'display_region_g' ))
//			$status->checkRequired ( $this, 'facility_region_g_id', $this->tr ( 'Region G' ) );
//		if ($this->setting ( 'display_region_h' ))
//			$status->checkRequired ( $this, 'facility_region_h_id', $this->tr ( 'Region H' ) );
//		if ($this->setting ( 'display_region_i' ))
//			$status->checkRequired ( $this, 'facility_region_i_id', $this->tr ( 'Region I' ) );
//			// $status->checkRequired ( $this, 'facility_city', t ( "City is required." ) );
//			// validate lat & long
//		
//		require_once 'Zend/Validate/Float.php';
//		require_once 'Zend/Validate/Between.php';
//		$lat = $this->getSanParam ( 'facility_latitude' );
//		$long = $this->getSanParam ( 'facility_longitude' );
//		$validator = new Zend_Validate_Float ();
//		$validbetween = new Zend_Validate_Between ( '-180', '180' );
//		if ($lat && (! $validator->isValid ( $lat ) || ! $validbetween->isValid ( $lat ))) {
//			$status->addError ( 'facility_latitude', t ( 'That latitude and longitude does not appear to be valid.' ) );
//		}
//		if ($long && (! $validator->isValid ( $long ) || ! $validbetween->isValid ( $long ))) {
//			$status->addError ( 'facility_longitude', t ( 'That latitude and longitude does not appear to be valid.' ) );
//		}
//		
//		// validate locations
//		$city_id = false;
//		$values = $this->getAllParams ();
//		require_once 'views/helpers/Location.php';
//		$facility_city_parent_id = regionFiltersGetLastID ( 'facility', $values );
//		
//		if ($values ['facility_city'] && ! $values ['is_new_city']) {
//			$city_id = Location::verifyHierarchy ( $values ['facility_city'], $facility_city_parent_id, $this->setting ( 'num_location_tiers' ) );
//			if ($city_id === false) {
//				$status->addError ( 'facility_city', t ( "That city does not appear to be located in the chosen region. If you want to create a new city, check the new city box." ) );
//			}
//		}
//		
//		// validate sponsors
//		$sponsor_date_array = $this->getSanParam ( 'sponsor_start_date' ); // may or may not be array
//		$sponsor_end_date_array = $this->getSanParam ( 'sponsor_end_date' );
//		$sponsor_id = ($this->getSanParam ( 'facility_sponsor_id' ) ? $this->getSanParam ( 'facility_sponsor_id' ) : null);
//		if (is_array ( $sponsor_id )) { // fallback, need at least one value to test if requiring sponsor dates
//			$sponsor_array = $sponsor_id;
//			$sponsor_id = $sponsor_id [0];
//		}
//		
//		if (@$this->setting ( 'require_sponsor_dates' )) {
//			if ($this->setting ( 'allow_multi_sponsors' )) { // and multiple sponsors option
//				if (! is_array ( $this->getSanParam ( 'facility_sponsor_id' ) )) {
//					$status->addError ( 'sponsor_end_date', t ( 'Sponsor dates are required.' ) . "\n" );
//				}
//				foreach ( $sponsor_array as $i => $val ) {
//					if (empty ( $sponsor_date_array [$i] ) && ! empty ( $val ))
//						$status->addError ( 'sponsor_start_date', t ( 'Sponsor dates are required.' ) . "\n" );
//					if (empty ( $sponsor_end_date_array [$i] ) && ! empty ( $val ))
//						$status->addError ( 'sponsor_end_date', t ( 'Sponsor dates are required.' ) . "\n" );
//				} // todo case where multiple array and no_allow_multi
//			}
//		}
//		
//		// end validation


		if ($status->hasError ()) {
			$status->setStatusMessage ( t ( 'The record could not be saved.' ) );
		} else {
			// save row
			if ( 1 ) {
				// map db field names to FORM field names

$activitydetailRow->ProjectCode = $this->getSanParam ( 'ProjectCode' );
date_default_timezone_set('America/Los_Angeles');
$now = new DateTime();
$activitydetailRow->timestamp = date('Y-m-d h:i:s', $now->getTimestamp());
$activitydetailRow->TranAmount = $this->getSanParam ( 'TranAmount' );
$activitydetailRow->GFA = $this->getSanParam ( 'GFA' );
//$activitydetailRow->AdjustmentLoadedDate = $this->getSanParam ( 'AdjustmentLoadedDate' );
$activitydetailRow->BudgetNbr = $this->getSanParam ( 'BudgetNbr' );
$activitydetailRow->BudgetName = $this->getSanParam ( 'BudgetName' );
$activitydetailRow->AccountCode = $this->getSanParam ( 'AccountCode' );
$activitydetailRow->PCAProjectCodeOrig = $this->getSanParam ( 'PCAProjectCodeOrig' );
$activitydetailRow->PCAProjectCodePosting = $this->getSanParam ( 'PCAProjectCodePosting' );
$activitydetailRow->PCAOptionCodeOrig = $this->getSanParam ( 'PCAOptionCodeOrig' );
$activitydetailRow->PCAOptionCodePosting = $this->getSanParam ( 'PCAOptionCodePosting' );
$activitydetailRow->PCATaskCodeOrig = $this->getSanParam ( 'PCATaskCodeOrig' );
$activitydetailRow->PCATaskCodePosting = $this->getSanParam ( 'PCATaskCodePosting' );
$activitydetailRow->TranFTE = $this->getSanParam ( 'TranFTE' );
//$activitydetailRow->Budget_Begin = $this->getSanParam ( 'Budget_Begin' );
//$activitydetailRow->Budget_End = $this->getSanParam ( 'Budget_End' );
$activitydetailRow->TranDate1 = $this->getSanParam ( 'TranDate1' );
$activitydetailRow->TranDescMod = $this->getSanParam ( 'TranDescMod' );
$activitydetailRow->TranReference2 = $this->getSanParam ( 'TranReference2' );
$activitydetailRow->TranReference4 = $this->getSanParam ( 'TranReference4' );
$activitydetailRow->Modified = $this->getSanParam ( 'Modified' );

				
//				// dupecheck
//				$dupe = new Facility ();
//				$select = $dupe->select ()->where ( 'location_id =' . $facilityRow->location_id . ' and is_deleted = 0 and facility_name = "' . $facilityRow->facility_name . '"' );
//				if (! $facilityRow->id && $dupe->fetchRow ( $select )) {
//					$status->status = '';
//					$status->setStatusMessage ( t ( 'The facility could not be saved. A facility with this name already exists in that location.' ) );
//					return false;
//				}


//file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'FinController:215 >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
//var_dump("activitydetailRow=", $activitydetailRow, "END");
//$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);

				
				$obj_id = $activitydetailRow->save ();
				
				//$_SESSION ['status'] = t ( 'The record was saved.' );
				if ($obj_id) {
					$status->setStatusMessage ( t ( 'The record was saved.' ) );
					return $obj_id;
				} else {
					unset ( $_SESSION ['status'] );
					$status->setStatusMessage ( t ( 'ERROR: The record could not be saved.' ) );
				}
			}
		}
		
		return false;
	}
	public function listAction() {
		require_once ('models/table/Facility.php');
		$rowArray = Facility::suggestionList ( $this->getParam ( 'query' ) );
		
		$this->sendData ( $rowArray );
	}

	public function listWithUnknownAction() {
		$this->listAction ();
	}
	
	public function editAction() {
	
//		if (! $this->hasACL ( 'edit_people' )) {
//			$this->doNoAccessError ();
//		}

		
		if ($id = $this->getSanParam ( 'id' )) {
			$activitydetail = new ActivityDetail ();
			$activitydetailRow = $activitydetail->fetchRow ( 'id = ' . $id );
			if ($activitydetailRow) {
				$activitydetailArray = $activitydetailRow->toArray ();
			} else {
				$activitydetailArray = array ();
				$activitydetailArray ['id'] = null;
			}
		} else {
			$activitydetailArray = array ();
			$activitydetailArray ['id'] = null;
		}
		
		$request = $this->getRequest ();
		$validateOnly = $request->isXmlHttpRequest ();
		
		if ($validateOnly)
			$this->setNoRenderer ();
		
		
		if ($request->isPost ()) {
			$rslt = $this->validateAndSave ( $activitydetailRow, false );
			// $rslt = $this->validateAndSave ( $facilityRow, (($this->getSanParam ( 'facility_name' ) != $facilityRow->facility_name) ? true : false) ); // checkName from _request, we dont need this anymore [bugfix/feature request]
			
			// validate
			$status = ValidationContainer::instance ();
			if ($validateOnly) {
				if ($rslt) {
					//$status->setRedirect ( '/site/edit/id/' . $id ); //TA:#382
				}
				$this->sendData ( $status );
			} else {
				$this->view->assign ( 'status', $status );
			}
		}
		
//		// list
//		$rowArray = OptionList::suggestionList ( 'activitydetail', array (
//				
//				'id' 
//		), false, 9999 );
//		$activitydetailArray = array ();
//		foreach ( $rowArray as $key => $val ) {
//			if ($val ['id'] != 0)
//				$activitydetailArray [] = $val;
//		}

		//valid ProjectCode		
//		$sql = "select distinct Project_Code from Project_Codes " .
//		       "where 1=1 " .
//		       " and BudgetNbr = '" . $activitydetailArray["BudgetNbr"] .
//		       " ' and Project_Status = 'Active'";
		       
//           		file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'FinCont:editAction >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
//				var_dump("sql=", $sql, "END");
//				$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
				
//		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
//		$gArray = $db->fetchAll ( $sql );
//
//		$activitydetailArray["ProjectCode"] = $gArray[0]['Project_Code'];

		$this->viewAssignEscaped ( 'activitydetail', $activitydetailArray );
		
//	file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'FinCont:edit:330 >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
//	var_dump("activitydetailRow=", $activitydetailRow["id"], $activitydetailRow["PCAProjectCodeOrig"], $activitydetailRow["PCAProjectCodePosting"], "END");
//	$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
	
		
	}
	
	public function deleteAction() {
		if (! $this->hasACL ( 'edit_people' )) {
			$this->doNoAccessError ();
		}
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );
		
		if ($id and ! Facility::isReferenced ( $id )) {
			$fac = new Facility ();
			$rows = $fac->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$fac->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That facility was deleted.' ) );
		} else if (! $id) {
			$status->setStatusMessage ( t ( 'That facility could not be found.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That facility is in use and could not be deleted.' ) );
		}
		
		// validate
		$this->view->assign ( 'status', $status );
	}
	public function deleteLocationAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->doNoAccessError ();
		}
		
		require_once 'models/table/TrainingLocation.php';
		$status = ValidationContainer::instance ();
		$id = $this->getSanParam ( 'id' );
		
		if ($id and ! TrainingLocation::isReferenced ( $id )) {
			$loc = new TrainingLocation ();
			$rows = $loc->find ( $id );
			$row = $rows->current ();
			if ($row) {
				$loc->delete ( 'id = ' . $row->id );
			}
			$status->setStatusMessage ( t ( 'That location was deleted.' ) );
		} else if (! $id) {
			$status->setStatusMessage ( t ( 'That location could not be found.' ) );
		} else {
			$status->setStatusMessage ( t ( 'That location is in use and could not be deleted.' ) );
		}
		
		// validate
		$this->view->assign ( 'status', $status );
	}
	public function searchLocationAction() {
		require_once ('models/table/OptionList.php');
		
		// location list
		$criteria = array ();
		list ( $criteria, $location_tier, $location_id ) = $this->getLocationCriteriaValues ( $criteria );
		$criteria ['training_location_name'] = $this->getSanParam ( 'training_location_name' );
		
		$criteria ['outputType'] = $this->getSanParam ( 'outputType' );
		
		$criteria ['go'] = $this->getSanParam ( 'go' );
		
		if ($criteria ['go']) {
			$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			
			$num_locs = $this->setting ( 'num_location_tiers' );
			list ( $field_name, $location_sub_query ) = Location::subquery ( $num_locs, $location_tier, $location_id, true );
			
			$sql = 'SELECT
								training_location.training_location_name,
								training_location.id , ' . implode ( ',', $field_name ) . '
							FROM training_location LEFT JOIN (' . $location_sub_query . ') as l ON training_location.location_id = l.id';
			
			$where = array (
					'training_location.is_deleted = 0' 
			);
			if ($criteria ['training_location_name']) {
				$where [] = $db->quoteInto(" training_location_name=?", $criteria ['training_location_name']);
			}
			$locationWhere = $this->getLocationCriteriaWhereClause ( $criteria, '', '' );
			if ($locationWhere) {
				$where [] = $locationWhere;
			}
			
			if ($where)
				$sql .= ' WHERE ' . implode ( ' AND ', $where );
			
			$sql .= " ORDER BY training_location_name ASC ";
			
			$rowArray = $db->fetchAll ( $sql );
			
			if ($criteria ['outputType']) {
				
				$this->sendData ( $rowArray );
			}
			
			$this->viewAssignEscaped ( 'results', $rowArray );
			$this->view->assign ( 'count', count ( $rowArray ) );
		}
		
		$this->view->assign ( 'criteria', $criteria );
		// location name
		$nameArray = OptionList::suggestionListValues ( 'training_location', 'training_location_name', false, false, false );
		$this->viewAssignEscaped ( 'location_names', $nameArray );
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
	}
	
	public function searchAction() {
		$this->view->assign ( 'mode', 'search' );
		
		//$criteria ['GFA'] = $this->getSanParam ( 'gfaInput' );
		
		//distinct GFA list
		$gArray = OptionList::suggestionList( 'GFA_List', 'GFA', false, 999, false, false, true);
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		
		$this->viewAssignEscaped ( 'gfa', $gfaArray );
		
		//distinct BudgetNbr list
		//$gArray = OptionList::suggestionList ( 'GFA_List', 'BudgetNbr', false, 999, false, false, true );
		$gArray = OptionList::suggestionList ( 'activitydetail', 'BudgetNbr', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'budgetNbr', $gfaArray );
		
		//distinct BudgetName list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'BudgetName', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'budgetName', $gfaArray );
		
		//distinct ProjectCode list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'ProjectCode', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'projectCode', $gfaArray );
		
//        		file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'FinCont:searchAction >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
//				var_dump("gfaArray=", $gfaArray, "END");
//				$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
		
		//distinct AccountCode list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'AccountCode', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'accountCode', $gfaArray );
		
		//distinct PCAProjectCodeOrig list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'PCAProjectCodeOrig', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'pcaProjectCodeOrig', $gfaArray );
		
		//distinct PCAProjectCodePosting list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'PCAProjectCodePosting', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'pcaProjectCodePosting', $gfaArray );
		
		//distinct PCAOptionCodeOrig list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'PCAOptionCodeOrig', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'pcaOptionCodeOrig', $gfaArray );
		
		//distinct PCAOptionCodePosting list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'PCAOptionCodePosting', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'pcaOptionCodePosting', $gfaArray );
		
		//distinct PCATaskCodeOrig list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'PCATaskCodeOrig', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'pcaTaskCodeOrig', $gfaArray );
		
		//distinct PCATaskCodePosting list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'PCATaskCodePosting', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'pcaTaskCodePosting', $gfaArray );
		
		//distinct TranDate1 list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranDate1', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranDate1', $gfaArray );
		
		//distinct TranFTE list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranFTE', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranFTE', $gfaArray );
		
		//distinct TranDescMod list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranDescMod', false, 9999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranDescMod', $gfaArray );
		
		//distinct TranReference1 list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranReference1', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranReference1', $gfaArray );
		
		//distinct tranReference2 list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranReference2', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranReference2', $gfaArray );
		
		//distinct TranReference3 list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranReference3', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranReference3', $gfaArray );
		
		//distinct tranReference4 list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'TranReference4', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'tranReference4', $gfaArray );
		
		//distinct modified list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'Modified', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'modified', $gfaArray );
		
		//distinct FiscalMonth list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'FiscalMonth', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'fiscalmonth', $gfaArray );
		
		//distinct FiscalYear list
		$gArray = OptionList::suggestionList ( 'activitydetail', 'FiscalYear', false, 999, false, false, true );
		$gfaArray = array ();
		foreach ( $gArray as $key => $val ) {
			//if ($val ['id'] != 0)
			$gfaArray [] = $val;
		}
		$this->viewAssignEscaped ( 'fiscalyear', $gfaArray );
		
		// TDPrimary key
		
		$this->activityDetailReport ();
	}
	
		public function activityDetailReport() {
		require_once ('views/helpers/DropDown.php');
		$criteria = array ();

		//find the first date in the database
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$sql = "SELECT MIN(TranDate1) as \"start\" FROM activitydetail ";
		$rowArray = $db->fetchAll ( $sql );
		$start_default = $rowArray [0] ['start'];
		$parts = explode('-', $start_default );
		$criteria ['start-year'] = $parts [0];
		$criteria ['start-month'] = $parts [1];
		$criteria ['start-day'] = $parts [2];
		
		$sql = "SELECT MAX(TranDate1) as \"end\" FROM activitydetail ";
		$rowArray = $db->fetchAll ( $sql );
		$end_default = $rowArray [0] ['end'];
		$parts = explode('-', $end_default );
		$criteria ['end-year'] = $parts [0];
		$criteria ['end-month'] = $parts [1];
		$criteria ['end-day'] = $parts [2];

		if ($this->getSanParam ( 'start-year' ))
		$criteria ['start-year'] = $this->getSanParam ( 'start-year' );
		if ($this->getSanParam ( 'start-month' ))
		$criteria ['start-month'] = $this->getSanParam ( 'start-month' );
		if ($this->getSanParam ( 'start-day' ))
		$criteria ['start-day'] = $this->getSanParam ( 'start-day' );
		if ($this->getSanParam ( 'end-year' ))
		$criteria ['end-year'] = $this->getSanParam ( 'end-year' );
		if ($this->getSanParam ( 'end-month' ))
		$criteria ['end-month'] = $this->getSanParam ( 'end-month' );
		if ($this->getSanParam ( 'end-day' ))
		$criteria ['end-day'] = $this->getSanParam ( 'end-day' );

		$beginDate = $criteria ['start-year'] . '-' . $criteria ['start-month'] . '-' . $criteria ['start-day'];
		$endDate = $criteria ['end-year'] . '-' . $criteria ['end-month'] . '-' . $criteria ['end-day'];
		
		
//id
//timestamp
//TDPrimaryKey
//ProjectCode
//TranAmount
//GFA
//AdjustmentLoadedDate
//BudgetNbr
//BudgetName
//AccountCode
//PCAProjectCodeOrig
//PCAProjectCodeOrig
//Budget_Begin
//Budget_End
//TranDate1
//TranDescMod
//TranReference2
//TranReference4
//Modified

		$criteria ['go'] = $this->getSanParam ( 'go' );
        $criteria ['doCount'] = ($this->view->mode == 'count');
        $criteria ['GFA'] = $this->getSanParam ( 'gfaInput' );
        $criteria ['BudgetNbr'] = $this->getSanParam ( 'budgetNbrInput' );
        $criteria ['BudgetName'] = $this->getSanParam ( 'budgetNameInput' );
        $criteria ['ProjectCode'] = $this->getSanParam ( 'projectCodeInput' );
        $criteria ['TranAmount'] = $this->getSanParam ( 'tranAmountInput' );
        $criteria ['AccountCode'] = $this->getSanParam ( 'accountCodeInput' );
        $criteria ['PCAProjectCodeOrig'] = $this->getSanParam ( 'pcaProjectCodeOrigInput' );
        $criteria ['PCAProjectCodePosting'] = $this->getSanParam ( 'pcaProjectCodePostingInput' );
           
        $criteria ['PCAOptionCodeOrig'] = $this->getSanParam ( 'pcaOptionCodeOrigInput' );
        $criteria ['PCAOptionCodePosting'] = $this->getSanParam ( 'pcaOptionCodePostingInput' );
        $criteria ['PCATaskCodeOrig'] = $this->getSanParam ( 'pcaTaskCodeOrigInput' );
        $criteria ['PCATaskCodePosting'] = $this->getSanParam ( 'pcaTaskCodePostingInput' );   
        $criteria ['TranFTE'] = $this->getSanParam ( 'tranFTEInput' );
           
        $criteria ['TranDate1'] = $this->getSanParam ( 'tranDate1Input' );
        $criteria ['TranDescMod'] = $this->getSanParam ( 'tranDescModInput' );
        $criteria ['TranReference1'] = $this->getSanParam ( 'tranReference1Input' );
        $criteria ['TranReference2'] = $this->getSanParam ( 'tranReference2Input' );
        $criteria ['TranReference3'] = $this->getSanParam ( 'tranReference3Input' );
        $criteria ['TranReference4'] = $this->getSanParam ( 'tranReference4Input' );
        $criteria ['Modified'] = $this->getSanParam ( 'modifiedInput' );
            
        $criteria ['TDPrimaryKey'] = $this->getSanParam ( 'tdprimarykeyInput' );
        $criteria ['FiscalMonth'] = $this->getSanParam ( 'fiscalmonthInput' );
        $criteria ['FiscalYear'] = $this->getSanParam ( 'fiscalyearInput' );
        
        $criteria ['showGFA'] =       true;
        $criteria ['showBudgetNbr'] =       true;
        $criteria ['showBudgetName'] = ($this->getSanParam ( 'showBudgetName' ));
        $criteria ['showProjectCode'] = ($this->getSanParam ( 'showProjectCode' ));
        $criteria ['showTranAmount'] = ($this->getSanParam ( 'showTranAmount' ));
        $criteria ['showAccountCode'] = ($this->getSanParam ( 'showAccountCode' ));
        $criteria ['showPCAProjectCodeOrig'] = ($this->getSanParam ( 'showPCAProjectCodeOrig' ));
        $criteria ['showPCAProjectCodePosting'] = ($this->getSanParam ( 'showPCAProjectCodePosting' ));
        
        $criteria ['showPCAOptionCodeOrig'] = ($this->getSanParam ( 'showPCAOptionCodeOrig' ));
        $criteria ['showPCAOptionCodePosting'] = ($this->getSanParam ( 'showPCAOptionCodePosting' ));
        $criteria ['showPCATaskCodeOrig'] = ($this->getSanParam ( 'showPCATaskCodeOrig' ));
        $criteria ['showPCATaskCodePosting'] = ($this->getSanParam ( 'showPCATaskCodePosting' ));
        $criteria ['showTranFTE'] = ($this->getSanParam ( 'showTranFTE' ));
        
        $criteria ['showTranDate1'] = ($this->getSanParam ( 'showTranDate1' ));
        $criteria ['showTranDescMod'] = ($this->getSanParam ( 'showTranDescMod' ));
        $criteria ['showTranReference1'] = ($this->getSanParam ( 'showTranReference1' ));
        $criteria ['showTranReference2'] = ($this->getSanParam ( 'showTranReference2' ));
        $criteria ['showTranReference3'] = ($this->getSanParam ( 'showTranReference3' ));
        $criteria ['showTranReference4'] = ($this->getSanParam ( 'showTranReference4' ));
        $criteria ['showModified'] = ($this->getSanParam ( 'showModified' ));
        
        $criteria ['showTDPrimaryKey'] = ($this->getSanParam ( 'showTDPrimaryKey' ));
        $criteria ['showFiscalMonth'] = ($this->getSanParam ( 'showFiscalMonth' ));
        $criteria ['showFiscalYear'] = ($this->getSanParam ( 'showFiscalYear' ));
        
		$criteria ['TranDate1_Begin'] = $beginDate;
	    $criteria ['TranDate1_End'] =   $endDate;
				
		$this->viewAssignEscaped ( 'criteria', $criteria );
        
        	if ($criteria ['go']) {
        		
        		$criteria ['GFA'] = $this->getSanParam ( 'gfaInput' );
        		$criteria ['BudgetNbr'] = $this->getSanParam ( 'budgetNbrInput' );
        		$criteria ['BudgetName'] = $this->getSanParam ( 'budgetNameInput' );
        		$criteria ['ProjectCode'] = $this->getSanParam ( 'projectCodeInput' );
        		$criteria ['TranAmount'] = $this->getSanParam ( 'tranAmountInput' );
        		$criteria ['AccountCode'] = $this->getSanParam ( 'accountCodeInput' );
          		$criteria ['PCAProjectCodeOrig'] = $this->getSanParam ( 'pcaProjectCodeOrigInput' );
           		$criteria ['PCAProjectCodePosting'] = $this->getSanParam ( 'pcaProjectCodePostingInput' );
           
                $criteria ['PCAOptionCodeOrig'] = $this->getSanParam ( 'pcaOptionCodeOrigInput' );
           		$criteria ['PCAOptionCodePosting'] = $this->getSanParam ( 'pcaOptionCodePostingInput' );
           		$criteria ['PCATaskCodeOrig'] = $this->getSanParam ( 'pcaTaskCodeOrigInput' );
           		$criteria ['PCATaskCodePosting'] = $this->getSanParam ( 'pcaTaskCodePostingInput' );
           		$criteria ['TranFTE'] = $this->getSanParam ( 'tranFTEInput' );
           		
            	$criteria ['TranDate1'] = $this->getSanParam ( 'tranDate1Input' );
             	$criteria ['TranDescMod'] = $this->getSanParam ( 'tranDescModInput' );
              	$criteria ['TranReference2'] = $this->getSanParam ( 'tranReference2Input' );
               	$criteria ['TranReference4'] = $this->getSanParam ( 'tranReference4Input' );
                $criteria ['Modified'] = $this->getSanParam ( 'modifiedInput' );
                
                $criteria ['TDPrimaryKey'] = $this->getSanParam ( 'tdprimarykeyInput' );
                $criteria ['FiscalMonth'] = $this->getSanParam ( 'fiscalmonthInput' );
                $criteria ['FiscalYear'] = $this->getSanParam ( 'fiscalyearInput' );
        		
        	    $sql = 'SELECT '; //todo test

			//if ($criteria ['doCount']) {
				//$sql .= ' COUNT(pt.person_id) as "cnt", pt.facility_name ';
			//} else {
				//$sql .= ' DISTINCT pt.id as "id", pt.facility_name, pt.training_start_date  ';
			//}
           
$sql .= ' DISTINCT la.id as "id", la.GFA, la.BudgetNbr , la.Budget_Begin, la.Budget_End, la.BudgetName, la.ProjectCode, la.TranAmount, la.AccountCode, la.PCAProjectCodeOrig, la.PCAProjectCodePosting, la.PCAOptionCodeOrig, la.PCAOptionCodePosting, la.PCATaskCodeOrig, la.PCATaskCodePosting, la.TranFTE, la.Budget_Begin, la.Budget_End, la.TranDate1, la.TranDescMod, la.TranReference1, la.TranReference2, la.TranReference3, la.TranReference4, la.Modified, la.TDPrimaryKey, la.FiscalMonth, la.FiscalYear ';
           		
           		$sql .= ' FROM activitydetail la';
				
           		$where = array();
           		
           		if($criteria['GFA']) $where []= ' trim(la.GFA) = \'' . $criteria ['GFA'] . '\'';
           		if($criteria['BudgetNbr']) $where []= ' trim(la.BudgetNbr) = \'' . $criteria ['BudgetNbr'] . '\'';
           		if($criteria['BudgetName']) $where []= ' trim(la.BudgetName) = \'' . $criteria ['BudgetName'] . '\'';
           		if($criteria['ProjectCode']) $where []= ' trim(la.ProjectCode) = \'' . $criteria ['ProjectCode'] . '\'';
           		
           		if($criteria['AccountCode']) $where []= ' trim(la.AccountCode) = \'' . $criteria ['AccountCode'] . '\'';
           		if($criteria['PCAProjectCodeOrig']) $where []= ' trim(la.PCAProjectCodeOrig) = \'' . $criteria ['PCAProjectCodeOrig'] . '\'';
           		if($criteria['PCAProjectCodePosting']) $where []= ' trim(la.PCAProjectCodePosting) = \'' . $criteria ['PCAProjectCodePosting'] . '\'';
           		
           		if($criteria['PCAOptionCodeOrig']) $where []= ' trim(la.PCAOptionCodeOrig) = \'' . $criteria ['PCAOptionCodeOrig'] . '\'';
           		if($criteria['PCAOptionCodePosting']) $where []= ' trim(la.PCAOptionCodePosting) = \'' . $criteria ['PCAOptionCodePosting'] . '\'';
           		if($criteria['PCATaskCodeOrig']) $where []= ' trim(la.PCATaskCodeOrig) = \'' . $criteria ['PCATaskCodeOrig'] . '\'';
           		if($criteria['PCATaskCodePosting']) $where []= ' trim(la.PCATaskCodePosting) = \'' . $criteria ['PCATaskCodePosting'] . '\'';
           		if($criteria['TranFTE']) $where []= ' trim(la.TranFTE) = \'' . $criteria ['TranFTE'] . '\'';
           		
           		if($criteria['TranDate1']) $where []= ' trim(la.TranDate1) = \'' . $criteria ['TranDate1'] . '\'';
           		if($criteria['TranDescMod']) $where []= ' trim(la.TranDescMod) = \'' . $criteria ['TranDescMod'] . '\'';
           		if($criteria['TranReference1']) $where []= ' trim(la.TranReference1) = \'' . $criteria ['TranReference1'] . '\'';
           		if($criteria['TranReference2']) $where []= ' trim(la.TranReference2) = \'' . $criteria ['TranReference2'] . '\'';
           		if($criteria['TranReference3']) $where []= ' trim(la.TranReference3) = \'' . $criteria ['TranReference3'] . '\'';
           		if($criteria['TranReference4']) $where []= ' trim(la.TranReference4) = \'' . $criteria ['TranReference4'] . '\'';
           		if($criteria['Modified']) $where []= ' trim(la.Modified) = \'' . $criteria ['Modified'] . '\'';
           		
           		if($criteria['TDPrimaryKey']) $where []= ' trim(la.TDPrimaryKey) = \'' . $criteria ['TDPrimaryKey'] . '\'';
           		if($criteria['FiscalMonth']) $where []= ' trim(la.FiscalMonth) = \'' . $criteria ['FiscalMonth'] . '\'';
           		if($criteria['FiscalYear']) $where []= ' trim(la.FiscalYear) = \'' . $criteria ['FiscalYear'] . '\'';
           		
           		$where []= ' la.TranDate1 >= \'' . $criteria ['TranDate1_Begin'] . '\'';
           		$where []= ' la.TranDate1 <= \'' . $criteria ['TranDate1_End'] . '\'';
           		
           		if ($where)
					$sql .= ' WHERE ' . implode(' AND ', $where);
        		}   
        		
//        		file_put_contents('/vagrant/vagrant/logs/php_debug.log', 'ReportsCont:4725 >' . PHP_EOL, FILE_APPEND | LOCK_EX); ob_start();
//				var_dump("sql=", $sql, "END");
//				$toss = ob_get_clean(); file_put_contents('/vagrant/vagrant/logs/php_debug.log', $toss . PHP_EOL, FILE_APPEND | LOCK_EX);
        		
        		//$rowArray = $db->fetchAll ( $sql . ' ORDER BY facility_name ASC ' );
        		$rowArray = $db->fetchAll ( $sql  );
        		
        		$this->viewAssignEscaped ( 'results', $rowArray ); 
        		$this->viewAssignEscaped ( 'count',  sizeOf($rowArray));
        		$this->viewAssignEscaped ( 'criteria', $criteria );
	}
	
	

	function addLocationAction() {
		require_once 'views/helpers/DropDown.php';
		
		// locations
		$this->viewAssignEscaped ( 'locations', Location::getAll () );
	}
	
	function viewLocationAction() {
		if (! $this->hasACL ( 'edit_course' )) {
			$this->view->assign ( 'viewonly', 'disabled="disabled"' );
		}
		
		require_once 'models/table/TrainingLocation.php';
		
		$this->view->assign ( 'id', $this->getParam ( 'id' ) );
		
		if ($this->getParam ( 'id' )) {
			require_once 'views/helpers/DropDown.php';
			
			$rowLocation = TrainingLocation::selectLocation ( $this->getParam ( 'id' ) )->toArray ();
			
			// locations
			$this->viewAssignEscaped ( 'locations', Location::getAll () );
			$region_ids = Location::getCityInfo ( $rowLocation ['location_id'], $this->setting ( 'num_location_tiers' ) );
			$rowLocation ['city_name'] = $region_ids [0];
			$region_ids = Location::regionsToHash ( $region_ids );
			$rowLocation = array_merge ( $rowLocation, $region_ids );
			
			$this->viewAssignEscaped ( 'rowLocation', $rowLocation );
			
			// see if it is referenced anywhere
			$this->view->assign ( 'okToDelete', (! TrainingLocation::isReferenced ( $this->getParam ( 'id' ) )) );
		}
		
		// location drop-down
		$locations = TrainingLocation::selectAllLocations ( $this->setting ( 'num_location_tiers' ) );
		$this->viewAssignEscaped ( 'tlocations', $locations );
	}
	
	/**
	 * Import a facility
	 */
	public function importAction() {
		$this->view->assign ( 'pageTitle', t ( 'Import a facility' ) );
		require_once ('models/table/Location.php');
		require_once ('models/table/Facility.php');
		
		// template redirect
		if ($this->getSanParam ( 'download' ))
			return $this->importFacilityTemplateAction ();
		
		if (! $this->hasACL ( 'import_facility' ))
			$this->doNoAccessError ();
			
			// CSV STUFF
		$filename = ($_FILES ['upload'] ['tmp_name']);
		if ($filename) {
			$facilityObj = new Facility ();
			$errs = array ();
			while ( $row = $this->_csv_get_row ( $filename ) ) {
			
			    //TA:#213
			    //INFORCE user to create files only in UTF-8 encoded:
			    //Option 1: Excel:Save as Unicode Text -> Notepad: replace tabs with commas, save as csv UTF-8
			    //Option 2: OpenOffice
			    //It is not required for english, but absolutelly required for special characteristics.
			    //If files saved in UTF-8 encoded, so we do not need this line
			    // $row = array_map("utf8_encode", $row); 
			
				$values = array ();
				if (! is_array ( $row ))
					continue; // sanity?
				if (! isset ( $cols )) { // set headers (field names)
					$cols = $row; // first row is headers (field names)
					continue;
				}
				$countValidFields = 0;
				if (! empty ( $row )) { // add
					foreach ( $row as $i => $v ) { // proccess each column
						if (empty ( $v ) && $v !== '0')
							continue;
						if ($v == 'n/a') // has to be able to process values from a data export
							$v = NULL;
						$countValidFields ++;
						$delimiter = strpos ( $v, ',' ); // is this field a comma seperated list too (or array)?
						if ($delimiter && $v [$delimiter - 1] != '\\') // handle arrays as field values(Export), and comma seperated values(import manual entry), and strings or int
							$values [$cols [$i]] = explode ( ',', $this->sanitize ( $v ) );
						else
							$values [$cols [$i]] = $this->sanitize ( $v );
					}
				}
				// done now all fields are named and in $values[my_field]
				if ($countValidFields) {
					// validate
					if (isset ( $values ['uuid'] )) {
						unset ( $values ['uuid'] );
					}
					if (isset ( $values ['id'] )) {
						unset ( $values ['id'] );
					}
					if (isset ( $values ['is_deleted'] )) {
						unset ( $values ['is_deleted'] );
					}
					if (isset ( $values ['created_by'] )) {
						unset ( $values ['created_by'] );
					}
					if (isset ( $values ['modified_by'] )) {
						unset ( $values ['modified_by'] );
					}
					if (isset ( $values ['timestamp_created'] )) {
						unset ( $values ['timestamp_created'] );
					}
					if (isset ( $values ['timestamp_updated'] )) {
						unset ( $values ['timestamp_updated'] );
					}
					if (! $this->hasACL ( 'approve_trainings' )) {
						unset ( $values ['approved'] );
					}
					if ($values ['sponsor_option_id']) {
						$sponsors = $this->_array_me ( $values ['sponsor_option_id'] ); // could be an array, we dont want one
						$values ['sponsor_option_id'] = $sponsors [0];
					}
					// required
					if (empty ( $values ['facility_name'] )) {
						$errs [] = t ( 'Error adding facility, Facility name cannot be empty.' );
						continue;
					}
					if (empty ( $values ['address_1'] )) {
						$values ['address_1'] = '';
					}
					if (empty ( $values ['address_2'] )) {
						$values ['address_2'] = '';
					}
					// locations
					$num_location_tiers = $this->setting ( 'num_location_tiers' );
					$bSuccess = true;
					$location_id = null;
					if ($values ['location_id'])
						$location_id = $values ['location_id'];
					$tier = 1;
					if (! $location_id) {
						for($i = 0; $i <= $num_location_tiers; $i ++) { // insert/find locations
							$r = 13 + $i; // first location field in csv row // could use this too: $values[t('Region A (Province)')]
							if (empty ( $row [$r] ) || $bSuccess == false)
								continue;
							$location_id = Location::insertIfNotFound ( $row [$r], $location_id, $tier );
							if (! $location_id) {
								$bSuccess = false;
								break;
							}
							$tier ++;
						}
					}
					if (! $bSuccess) {
						$errs [] = t ( 'Error locating/creating region or city:' ) . ' ' . $row [$r] . ' ' . t ( 'Facility' ) . ': ' . $values ['facility_name'];
						continue; // couldnt save location
					}
					$values ['location_id'] = $location_id;
					// dupecheck
					if ($location_id) {
						$dupe = new Facility ();
						$select = $dupe->select ()->where ( 'location_id =' . $location_id . ' and is_deleted = 0 and facility_name = "' . $values ['facility_name'] . '"' );
						if ($dupe->fetchRow ( $select )) {
							$errs [] = t ( 'The facility could not be saved. A facility with this name already exists in that location.' ) . ' ' . t ( 'Facility' ) . ': ' . $values ['facility_name'];
							$bSuccess = false;
						}
					} else {
						$location_id = null;
					}
					// save
					if ($bSuccess) {
						try {

							$tableObj = $facilityObj->createRow ();
							$tableObj = ITechController::fillFromArray ( $tableObj, $values );
							$tableObj->type_option_id = $this->_importHelperFindOrCreate ( 'facility_type_option', 'id', $tableObj->type_option_id );
							if ($values ['type_option_id'] && ! $tableObj->type_option_id) {
								$errs [] = t ( "Couldn't save facility type for facility:" ) . ' ' . $tableObj->facility_name;
							}
							$row_id = $tableObj->save ();
						} catch ( Exception $e ) {

							$errored = 1;
							$errs [] = nl2br ( $e->getMessage () ) . ' ' . t ( 'ERROR: The facility could not be saved.' );
						}
						if (! $row_id)
							$errored = 1;
							
							// save linked tables
						if ($row_id) {
							if ($sponsors || $values ['sponsor_start_date'] || $values ['sponsor_end_date']) {
								$dates1 = $this->_array_me ( $values ['sponsor_start_date'] );
								$dates2 = $this->_array_me ( $values ['sponsor_end_date'] );
								foreach ( $sponsors as $i => $phrase ) // insert or get id
									$sponsors [$i] = $this->_importHelperFindOrCreate ( 'facility_sponsor_option', 'facility_sponsor_phrase', $phrase ); // facility_type_option_id multiAssign (insert via helper)
								if (! Facility::saveSponsors ( $row_id, $sponsors, $dates1, $dates2 )) { // save
									$errs [] = t ( 'There was an error saving sponsor data though.' ) . ' ' . t ( 'Facility' ) . ': ' . $tableObj->facility_name;
								}
							}
						}
					}
					// sucess - done
				} // loop
			}
			// done processing rows
			
			$_POST ['redirect'] = null;
			$status = ValidationContainer::instance ();
			if (empty ( $errored ) && empty ( $errs ))
				$stat = t ( 'Your changes have been saved.' );
			else
				$stat = t ( 'Error importing data. Some data may have been imported and some may not have.' );
			
			foreach ( $errs as $errmsg )
				$stat .= '<br>' . 'Error: ' . htmlspecialchars ( $errmsg, ENT_QUOTES );
			
			$status->setStatusMessage ( $stat );
			$this->view->assign ( 'status', $status );
		}
		// done with import
	}
	
	/**
	 * A template for importing a Facility
	 */
	public function importFacilityTemplateAction() {
		// gimme a csv template for an example Facility
		$sorted = array (

				array (
						'id' => '',
						'facility_name' => '',
						'location_id' => '',
						'address_1' => '',
						'address_2' => '',
						'postal_code' => '',
						'phone' => '',
						'fax' => '',
						'type_option_id' => '',
						'facility_type_phrase' => '',
						'facility_comments' => '',
						'custom_1' => '',
						'sponsor_option_id' => '', 
				) 
		);
		// add some regions
		$num_location_tiers = $this->setting ( 'num_location_tiers' );
		$regionNames = array (
				'',
				t ( 'Region A (Province)' ),
				t ( 'Region B (Health District)' ),
				t ( 'Region C (Local Region)' ),
				t ( 'Region D' ),
				t ( 'Region E' ),
				t ( 'Region F' ),
				t ( 'Region G' ),
				t ( 'Region H' ),
				t ( 'Region I' ) 
		);
		for($i = 1; $i < $num_location_tiers; $i ++) {
			// add regions
			$sorted [0] [$regionNames [$i]] = '';
		}
		// add city region
		$sorted [0] [t ( 'City' )] = '';
		
		// done, output a csv
		if ($this->getSanParam ( 'outputType' ) == 'csv')

			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
	}
	
	/**
	 * Import a training location
	 */
	public function importLocationAction() {
		$this->view->assign ( 'pageTitle', t ( 'Import a training location' ) );
		require_once ('models/table/Location.php');
		require_once ('models/table/TrainingLocation.php');
		
		// template redirect
		if ($this->getSanParam ( 'download' ))
			return $this->importLocationTemplateAction ();
		
		if (! $this->hasACL ( 'import_training_location' ))
			$this->doNoAccessError ();
			
			// CSV STUFF
		$filename = ($_FILES ['upload'] ['tmp_name']);
		if ($filename) {
			$trainingLocationObj = new TrainingLocation ();
			$errs = array ();
			while ( $row = $this->_csv_get_row ( $filename ) ) {
			    //TA:#213
			    //INFORCE user to create files only in UTF-8 encoded:
			    //Option 1: Excel:Save as Unicode Text -> Notepad: replace tabs with commas, save as csv UTF-8
			    //Option 2: OpenOffice
			    //It is not required for english, but absolutelly required for special characteristics.
			    //If files saved in UTF-8 encoded, so we do not need this line
			    // $row = array_map("utf8_encode", $row); 
				$values = array ();
				if (! is_array ( $row ))
					continue; // sanity?
				if (! isset ( $cols )) { // set headers (field names)
					$cols = $row; // first row is headers (field names)
					continue;
				}
				$countValidFields = 0;
				if (! empty ( $row )) { // add
					foreach ( $row as $i => $v ) { // proccess each column
						if (empty ( $v ) && $v !== '0')
							continue;
						if ($v == 'n/a') // has to be able to process values from a data export
							$v = NULL;
						$countValidFields ++;
						$delimiter = strpos ( $v, ',' ); // is this field a comma seperated list too (or array)?
						if ($delimiter && $v [$delimiter - 1] != '\\') // handle arrays as field values(Export), and comma seperated values(import manual entry), and strings or int
							$values [$cols [$i]] = explode ( ',', $this->sanitize ( $v ) );
						else
							$values [$cols [$i]] = $this->sanitize ( $v );
					}
				}
				// done now all fields are named and in $values['my_field']
				if ($countValidFields) {
					// validate
					if (isset ( $values ['uuid'] )) {
						unset ( $values ['uuid'] );
					}
					if (isset ( $values ['id'] )) {
						unset ( $values ['id'] );
					}
					if (isset ( $values ['is_deleted'] )) {
						unset ( $values ['is_deleted'] );
					}
					if (isset ( $values ['created_by'] )) {
						unset ( $values ['created_by'] );
					}
					if (isset ( $values ['modified_by'] )) {
						unset ( $values ['modified_by'] );
					}
					if (isset ( $values ['timestamp_created'] )) {
						unset ( $values ['timestamp_created'] );
					}
					if (isset ( $values ['timestamp_updated'] )) {
						unset ( $values ['timestamp_updated'] );
					}
					// required
					if (empty ( $values ['training_location_name'] )) {
						$errs [] = t ( 'Error adding training location, training location name cannot be empty.' );
					}			
					// locations
					$num_location_tiers = $this->setting ( 'num_location_tiers' );
					$bSuccess = true;
					$location_id = null;
					if ($values ['location_id'])
						$location_id = $values ['location_id'];
					$tier = 1;
					if (! $location_id) {
						for($i = 0; $i <= $num_location_tiers; $i ++) { // insert/find locations
							$r = 1 + $i; // first location field in csv row // could use this too: $values[t('Region A (Province)')]
							if (empty ( $row [$r] ) || $bSuccess == false) 	
								continue;
							$location_id = Location::insertIfNotFound ( $row [$r], $location_id, $tier );
							if (! $location_id) {
								$bSuccess = false;
								break;
							}
							$tier ++;
						}
					}
					if (! $bSuccess || ! $location_id) {
						$errs [] = t ( 'Error locating/creating region or city:' ) . ' ' . $row [$r] . ' ' . t ( 'Training Location' ) . ': ' . $values ['training_location_name'];
						continue; // couldnt save location
					}
					$values ['location_id'] = $location_id;				
					// dupecheck
					$dupe = new TrainingLocation ();
					$select = $dupe->select ()->where ( 'location_id =' . $location_id . ' and is_deleted = 0 and training_location_name = "' . $values ['training_location_name'] . '"' );
					if ($dupe->fetchRow ( $select )) {
						$errs [] = t ( 'The training location could not be saved. A training location with this name already exists in that location.' ) . ' ' . t ( 'training location' ) . ': ' . $values ['training_location_name'];
						$bSuccess = false;
					}
					if (! $bSuccess)
						continue;
						// save
					try {
						$tableObj = $trainingLocationObj->createRow ();
						$tableObj->training_location_name = $values ['training_location_name'];
						$tableObj->location_id = $location_id;
						$row_id = $tableObj->save ();
					} catch ( Exception $e ) {
						$errored = 1;
						$errs [] = nl2br ( $e->getMessage () ) . ' ' . t ( 'ERROR: The training location could not be saved.' );
					}
					if (! $row_id)
						$errored = 1;
					// sucess - done
					
				} // loop
			}
			// done processing rows
			$_POST ['redirect'] = null;
			$status = ValidationContainer::instance ();
			if (empty ( $errored ) && empty ( $errs ))
				$stat = t ( 'Your changes have been saved.' );
			else
				$stat = t ( 'Error importing data. Some data may have been imported and some may not have.' );
			
			foreach ( $errs as $errmsg )
				$stat .= '<br>' . 'Error: ' . htmlspecialchars ( $errmsg, ENT_QUOTES );
			
			$status->setStatusMessage ( $stat );
			$this->view->assign ( 'status', $status );
		}
		// done with import
	}
	
	/**
	 * A template for importing a training_location
	 */
	public function importLocationTemplateAction() {
		// gimme a csv template for an example training location
		$sorted = array (
				array (
						'training_location_name' => 'Sample Location' 
				) 
		);
		// add on a few regions equal to the number the site is using... examples!
		$num_location_tiers = $this->setting ( 'num_location_tiers' );
		$regionNames = array (
				'',
				t ( 'Region A (Province)' ),
				t ( 'Region B (Health District)' ),
				t ( 'Region C (Local Region)' ),
				t ( 'Region D' ),
				t ( 'Region E' ),
				t ( 'Region F' ),
				t ( 'Region G' ),
				t ( 'Region H' ),
				t ( 'Region I' ) 
		);
		for($i = 1; $i < $num_location_tiers; $i ++) {
			$sorted [0] [$regionNames [$i]] = '';
		}
		// add city region
		$sorted [0] [t ( 'City' )] = t ( 'City' );
		// done, output a csv
		if ($this->getSanParam ( 'outputType' ) == 'csv')
			$this->sendData ( $this->reportHeaders ( false, $sorted ) );
	}
	
// 	public function is_utf8($string) {
	
// 	    // From http://w3.org/International/questions/qa-forms-utf-8.html
// 	    return preg_match('%^(?:
//           [\x09\x0A\x0D\x20-\x7E]            # ASCII
//         | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
//         |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
//         | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
//         |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
//         |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
//         | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
//         |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
//     )*$%xs', $string);
	
// 	}
}
