<?php

class Callrecording implements BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}
    public function install() {}
    public function uninstall() {}
    public function backup() {}
    public function restore($backup) {}
	public static function myConfigPageInits() {
		 return array("routing"); 
	}
    public function doConfigPageInit($page) {
		$request = $_REQUEST;
		if($page == "callrecording"){
			$type = isset($request['type']) ? $request['type'] : 'setup';
			$view = isset($request['view']) ? $request['view'] : 'form';
			$action = isset($request['action']) ? $request['action'] :  '';
			if (isset($request['delete'])) $action = 'delete';

			$callrecording_id = isset($request['callrecording_id']) ? $request['callrecording_id'] :  false;
			$description = isset($request['description']) ? $request['description'] :  '';
			$callrecording_mode = isset($request['callrecording_mode']) ? $request['callrecording_mode'] :  '';
			$dest = isset($request['dest']) ? $request['dest'] :  '';

			if (isset($request['goto0']) && $request['goto0']) {
				$dest = $request[ $request['goto0'].'0' ];
			}

			switch ($action) {
				case 'add':
					$request['extdisplay'] = callrecording_add($description, $callrecording_mode, $dest);
					needreload();
					redirect_standard('extdisplay', 'view');
				break;
				case 'edit':
					callrecording_edit($callrecording_id, $description, $callrecording_mode, $dest);
					needreload();
					redirect_standard('extdisplay', 'view');
				break;
				case 'delete':
					callrecording_delete($callrecording_id);
					needreload();
					redirect_standard();
				break;
			}

		}
		if($page == "routing"){
			$viewing_itemid = $request['id'];
			$action = (isset($request['action']))?$request['action']:null;
			$route_id = $viewing_itemid;
			//dbug("got request for callrecording process for route: $route_id action: $action");
			if (isset($request['Submit']) ) {
				$action = (isset($action))?$action:'editroute';
			}
			// $action won't be set on the redirect but callrecordingAddRoute will be in the session
			//
			if (!$action && !empty($_SESSION['callrecordingAddRoute'])) {
				callrecording_adjustroute($route_id,'delayed_insert_route',$_SESSION['callrecordingAddRoute']);
				unset($_SESSION['callrecordingAddRoute']);
			} elseif ($action){
				callrecording_adjustroute($route_id,$action,$request['callrecording']);
			}
		}
    }

	public function getActionBar($request) {
		$buttons = array();

		switch($request['display']) {
			case 'callrecording':
				$buttons = array(
					'delete' => array(
						'name' => 'delete',
						'id' => 'delete',
						'value' => _('Delete')
					),
					'reset' => array(
						'name' => 'reset',
						'id' => 'reset',
						'value' => _('Reset')
					),
					'submit' => array(
						'name' => 'submit',
						'id' => 'submit',
						'value' => _('Submit')
					)
				);
				if (empty($request['extdisplay'])) {
					unset($buttons['delete']);
				}
				if($request['view'] != 'form'){
					unset($buttons);
				}
			break;
		}
		return $buttons;
	}
}