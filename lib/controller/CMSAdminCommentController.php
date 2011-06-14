<?php
/**
 * controller for comments
 */

class CMSAdminCommentController extends AdminComponent {

  public $module_name = "comments";
  public $model_class = 'CmsComment';
	public $model_name = "cms_comment";
	public $display_name = "Comments";
	public $scaffold_columns = array(
    "author_name"   =>array(),
    "comment"   =>array(),
    "time"   =>array(),
  );
  public $filter_columns = array("author_name");
  public $default_order = "time";
	public $default_direction="DESC";
	public static $permissions = array("create","edit","delete","admin");

	/**
	 * add a new sub nav option
	 */
	public function controller_global() {
    $this->sub_links["spammed"]="Comments in Spam";
    $this->sub_links["allowed"]="Comments Approved";
    $this->sub_links["pending"]="Comments Pending";
	}
	/**
	 * list page for this module
	 * shows only active comments (ie status =1) and paginates them
	 **/
	public function index() {
		$this->set_order();
		$this->display_action_name = 'List Comments';
		$this->all_rows = $this->model->filter(array('status'=>1))->order($this->get_order())->page($this->this_page, $this->list_limit);
		if(!$this->all_rows) $this->all_rows=array();
		$this->use_view="cindex";
	}
	/**
	 * list page for all 'spam' comments (ie status=2)
	 */
	public function spammed() {
		$this->use_view="cindex";
	  $this->set_order();
		$this->display_action_name = 'Comments in Spam';
		$this->all_rows = $this->model->filter(array('status'=>2))->order($this->get_order())->page($this->this_page, $this->list_limit);
		if(!$this->all_rows) $this->all_rows=array();
	}
	public function allowed() {
		$this->use_view="cindex";
	  $this->set_order();
		$this->display_action_name = 'Comments Approved';
		$this->all_rows = $this->model->filter(array('status'=>1))->order($this->get_order())->page($this->this_page, $this->list_limit);
		if(!$this->all_rows) $this->all_rows=array();
	}
	public function pending() {
		$this->use_view="cindex";
	  $this->set_order();
		$this->display_action_name = 'Comments Pending';
		$this->all_rows = $this->model->filter(array('status'=>0))->order($this->get_order())->page($this->this_page, $this->list_limit);
		if(!$this->all_rows) $this->all_rows=array();
	}	
	/**
	 * turn a comment into spam (no, not the meat product...)
	 */
	public function spam() {
	  $comment = new CmsComment(Request::get("id"));
	  if($comment->update_attributes(array("status"=>2)) ) {
	    Session::add_message("Comment marked as spam");
	  }
	  $this->redirect_to(array("action"=>"index"));
	}
	/**
	 * approve a comment - so set its status to 1
	**/
	public function approve() {
	  $comment = new CmsComment(Request::get("id"));
	  if($comment->update_attributes(array("status"=>1)) ) {
	    Session::add_message("Comment approved");
	  }
	  $this->redirect_to(array("action"=>"index"));
	}

}

?>