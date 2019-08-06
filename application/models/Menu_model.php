<?php
	/**
	* Menu Model
	*/
	class Menu_model extends MY_Model{
		
		function __construct(){
			parent::__construct();
			$this->table = 'menus';
			$this->key = 'menu_id';
			$this->order = array('menu_id', 'DESC');
		}

		function menu_parent($id){
			$this->db->select('menu_id');
			$this->db->where('menu_parent', $id);
			$query = $this->db->get($this->table);
			if ($query->num_rows())  {
	            return TRUE;
	        }
			return FALSE;
		}

		function menu_target_category($id){
			//var_dump($id);
			$this->db->select('taxonomy_name, taxonomy_alias');
			$this->db->where('taxonomy_id', $id);
			$query = $this->db->get('taxonomy');
			if ($query->num_rows())  {
	            return $query->row();
	        }
			return FALSE;
		}

		function Menu_Option($data_menus, $position, $id_parent = 0, $level = '-1') {
			$html = '';
			$menu_tmp = array();
			foreach ($data_menus as $key => $item) {
				if ($item->menu_parent == $id_parent && $item->menu_position == $position) {
					$menu_tmp[] = $item;
					unset($data_menus[$key]);
				}
			}
			if($menu_tmp) {
				$level++;
				foreach ($menu_tmp as $key => $item) {
					$html .= '<option value="'.$item->menu_id.'">'.str_repeat("— ", $level).$item->menu_title.'</option>';
					$html .= $this->Menu_Option($data_menus, $item->menu_id, $level);
				}
			}
			return $html;
		}

		function Menu_Show_Admin($data_menus, $position, $id_parent = 0, $level = '-1') {
			$html = '';
			$menu_tmp = array();
			foreach ($data_menus as $key => $item) {
				if ($item->menu_parent == $id_parent && $item->menu_position == $position) {
					$menu_tmp[] = $item;
					unset($data_menus[$key]);
				}
			}
			
			if($menu_tmp) {
				$level++;
				$html .= '<ul class="reorder-menus reorder-menus-'.$position.'-list">';
				foreach ($menu_tmp as $key => $item){
					$html .= '<li class="ui-sortable-handle" id="items_menu_'.$item->menu_id.'">';
					$html .= '<div class="this_menu_item" style="width: calc(100% - '.($level*20).'px); float: right; clear: both">';
						$html .= '<a><span id="textmenu'.$item->menu_id.'">'.$item->menu_title.'</span>
								<i id_menus="'.$item->menu_id.'" class="trash_item fa fa-trash-o"></i>
								<i id_menus="'.$item->menu_id.'" class="edit_item fa fa-pencil"></i>
							</a>';
					$html .= '</div>';
					$html .= '<div class="this_menu_item_edit">
							<input placeholder="Tên menu" style="margin-bottom: 5px" type="text" value="'.$item->menu_title.'"/>';
							switch ($item->menu_type) {
								case 1:
									$html .= '<input placeholder="Link menu" style="margin-bottom: 5px" type="text" value="'.$item->menu_alias.'"/>';
									break;
								case 2:
									$category = $this->menu_target_category($item->menu_target);
									if($category){
										$html .= '<span class="menu_target">Danh mục: <a target="_blank" href="'.base_url().'category/'.$category->taxonomy_alias.'">'.$category->taxonomy_name.'</a></span>';
									}else{
										$html .= '<span class="menu_target">Danh mục: <a target="_blank" href="#">Error</a></span>';
									}									
									break;
								case 3:
									$html .= '<input placeholder="Link menu" style="margin-bottom: 5px" type="text" value="'.$item->menu_alias.'"/>';
									break;
							}
							
					$html .='<input placeholder="Class" type="text" value="'.$item->menu_class.'"/>
							<i id_menus="'.$item->menu_id.'" data-type="'.$item->menu_type.'" class="update_menu fa fa-check"></i>
						</div>';
					$html .= $this->Menu_Show_Admin($data_menus, $position, $item->menu_id, $level);
					$html .= '</li>';
				}
				$html .= '</ul>';
			}
			return $html;
		}

		function get_alias_menu($type, $menu_target, $menu_alias){
			switch ($type) {
				case '1':
					return $menu_alias;
					break;
				case '2':
					$category = $this->menu_target_category($menu_target);
					return base_url().'category/'.$category->taxonomy_alias;
					break;
				default:
					return '#';
					break;
			}
		}

		function mainMenu(){
			$input['where'] = array('menu_parent' => 0, 'menu_position' => 'top');
			$input['order'] = array('menu_order','ASC');
			$menu = $this->menu_model->get_list($input);
			$html = '';
			if($menu){
				foreach ($menu as $key => $item) {
					$html  .= '<li class="menu menuList">';
						$html  .= '<div class="menu-item">';
							$html  .= '<a href="'.$this->get_alias_menu($item->menu_type, $item->menu_target, $item->menu_alias).'" class="nav navItem">'.$item->menu_title.'</a>';
						$html  .= '</div>';
					$html  .= '</li>';
				}
			}
			return $html;

		}

		function menuFixed(){
			$input['where'] = array('menu_parent' => 0, 'menu_position' => 'fixed');
			$input['order'] = array('menu_order','ASC');
			$menu = $this->menu_model->get_list($input);
			$html = '';
			if($menu){
				foreach ($menu as $key => $item) {
					$subMenuFixed = $this->subMenuFixed($item->menu_id);
					$class = '';
					if($subMenuFixed !=''){
						$class = 'drop-menu';
					}
					$html .='<li class="menu menuList">';
						$html .='<a class="'.$class.'" href="'.$this->get_alias_menu($item->menu_type, $item->menu_target, $item->menu_alias).'">';
							if($item->menu_class != ''){
								$html .='<i class="fa '.$item->menu_class.'" aria-hidden="true"></i>';
							}							
							$html .='<span>'.$item->menu_title.'</span>';
							$html .='<i class="drop-menu-icon fa fa-chevron-right" aria-hidden="true"></i>';
						$html .='</a>';
						if($subMenuFixed !=''){
							$html .='<div class="menuTopSub">';
								$html .='<ul>';
									$html .= $subMenuFixed;
								$html .='</ul>';
							$html .='</div>';								
						}
					
					$html .='</li>';
				}
			}
			return $html;
		}

		function subMenuFixed($id){
			$input['where'] = array('menu_parent' => $id, 'menu_position' => 'fixed');
			$input['order'] = array('menu_order','ASC');
			$menu = $this->menu_model->get_list($input);
			$html = '';
			if($menu){
				foreach ($menu as $key => $item) {
					//$subMenuFixed = $this->subMenuFixed($item->menu_id);

					
					$html .='<li class="menu menuListSub">';
						$html .='<a href="'.$this->get_alias_menu($item->menu_type, $item->menu_target, $item->menu_alias).'">';
							if($item->menu_class != ''){
								$html .='<i class="fa '.$item->menu_class.'" aria-hidden="true"></i>';
							}
							$html .='<span>'.$item->menu_title.'</span>';
						$html .='</a>';
					$html .='</li>';
				}
			}
			return $html;
		}

		function menuFooter(){
			$input['where'] = array('menu_parent' => 0, 'menu_position' => 'bottom');
			$input['order'] = array('menu_order','ASC');
			$menu = $this->menu_model->get_list($input);
			$html = '';
			if($menu){
				foreach ($menu as $key => $item){
					$html .= '<li><a href="'.$this->get_alias_menu($item->menu_type, $item->menu_target, $item->menu_alias).'">'.$item->menu_title.'</a></li>';
				}
			}
			return $html;
		}
	}