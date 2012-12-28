<?php

/* Include all other system or application files that you need to reference here.*/


require_once('data/SugarBean.php'); /*Include this file since we are extending SugarBean*/
require_once('include/utils.php'); /* Include this file if you want access to Utility methods such as return_module_language,return_mod_list_strings_language, etc ..*/
require_once('modules/ForumTopics/ForumTopic.php');

class Forum extends SugarBean {
	/* Foreach instance of the bean you will need to access the fields in the table.
	 * So define a variable for each one of them, the varaible name should be same as the field name
	 * Use this module's vardef file as a reference to create these variables.
	 */
    var $id;
    var $date_entered;
    var $category;
    var $created_by;
    var $date_modified;
    var $modified_user_id;
    var $deleted;
    var $title;
    var $description;
    
    // non-db fields
    var $recent_thread_title;
    var $recent_thread_id;
    var $recent_thread_modified_name;
    var $recent_thread_modified_id;
    var $created_by_user_name;
    var $modified_by_user_name;



    var $category_ranking;

	/* End field definitions*/

	/* variable $table_name is used by SugarBean and methods in this file to constructs queries
	 * set this variables value to the table associated with this bean.
	 */
	var $table_name = 'forums';
	
	/*This  variable overrides the object_name variable in SugarBean, wher it has a value of null.*/
	var $object_name = 'Forums';
	
	/**/
	var $module_dir = 'Forums';
	
	/* This is a legacy variable, set its value to true for new modules*/
	var $new_schema = true;

	/* $column_fields holds a list of columns that exist in this bean's table. This list is referenced
	 * when fetching or saving data for the bean. As you modify a table you need to keep this up to date.
	 */
	var $column_fields = Array(
            'id',
            'date_entered',
            'category',
            'created_by',
            'date_modified',
            'modified_user_id',
            'created_by_user_name',
            'deleted',
            'title',
            'description',
    );

	/*This bean's constructor*/
	function Forum() {
		/*Call the parent's constructor which will setup a database connection, logger and other settings.*/
		parent::SugarBean();
	}

	/* This method should return the summary text which is used to build the bread crumb navigation*/
	/* Generally from this method you would return value of a field that is required and is of type string*/ 
	function get_summary_text()
	{
		return "$this->title";
	}

function bean_implements($interface){
	switch($interface){
		case 'ACL': return true;
	}
	return false;
}

	/* This method is used to generate query for the list form. The base implementation of this method
	 * uses the table_name and list_field varaible to generate the basic query and then  adds the custom field
	 * join and team filter. If you are implementing this function do not forget to consider the additional conditions.
	 */
	function create_list_query($order_by, $where)
	{
		//Build the join condition for custom fields, the custom field array was populated
		//when you invoked the constructor for the SugarBean.
		$custom_join = $this->custom_fields->getJOIN();
        
   		//Build the select list for the query. 
        $query = "SELECT forums.* ";

		//If custom fields exist append the select list here.
        if($custom_join){
			$query .= $custom_join['select'];
		}
		
		//append the WHERE clause to the $query string.
        $query .= " FROM forums ";

		//Add custom fields join condition.
		if($custom_join){
			$query .= $custom_join['join'];
		}

		//Append additional filter conditions.
		$where_auto = " (forums.deleted=0) ";

		//if the function recevied a where clause append it.
		if($where != "")
			$query .= "where $where AND $where_auto";
		else
			$query .= "where ".$where_auto;

		//append the order by clause.
		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY forums.title";

		return $query;
	}

	function create_export_query()
	{
		return $this->create_list_query("", "");
	}
	

	function fill_in_additional_list_fields()
	{
	  $mostRecentThreadWithPost =
	    $this->db->fetchByAssoc(
		  $GLOBALS['db']->query(
		    "select threads.* ".
		    "from threads, posts ".
		    "where threads.forum_id='".$GLOBALS['db']->quote($this->id)."' ".
		    "and   threads.id = posts.thread_id ".
            "and threads.deleted=0 and posts.deleted=0 ".
		    "order by posts.date_modified desc "
		  )
		);

	  $mostRecentThreadWithoutPost =
	    $this->db->fetchByAssoc(
		  $GLOBALS['db']->query(
		    "select * ".
		    "from threads ".
		    "where forum_id='".$GLOBALS['db']->quote($this->id)."' ".
            "and deleted=0 ".
		    "order by date_modified desc "
		  )
		);
	  
    /*
      echo "with post:<BR><pre>"; print_r($mostRecentThreadWithPost); echo "</pre>";
      echo "without post:<BR><pre>"; print_r($mostRecentThreadWithoutPost); echo "</pre>";
    */
      
	  if(!empty($mostRecentThreadWithPost))
      {
        if($mostRecentThreadWithoutPost['date_modified'] > $mostRecentThreadWithPost['date_modified'])
          $mostRecentThread = $mostRecentThreadWithoutPost;
        else
          $mostRecentThread = $mostRecentThreadWithPost;
      }
	  else if(!empty($mostRecentThreadWithoutPost))
      {
	  	$mostRecentThread = $mostRecentThreadWithoutPost;
      }

	  $this->recent_thread_title = (isset($mostRecentThread) ? $mostRecentThread['title'] : null);
	  $this->recent_thread_id = (isset($mostRecentThread) ? $mostRecentThread['id'] : null);
      $this->recent_thread_modified_id = (isset($mostRecentThread) ? $mostRecentThread['modified_user_id'] : null);
	  $this->recent_thread_modified_name = (isset($mostRecentThread) ? get_assigned_user_name($mostRecentThread['modified_user_id']) : null);
      
      $this->category_ranking = ForumTopic::get_order($this->category);
	}

  function fill_in_additional_detail_fields()
  {
    // Fill in the assigned_user_name
    //$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
    $this->created_by_user_name = get_assigned_user_name($this->created_by);
    $this->modified_by_user_name = get_assigned_user_name($this->modified_user_id);
    $this->category_ranking = ForumTopic::get_order($this->category);
  }

  function incrementThreadCount($increment_by = "1", $id = '')
  {
    if($id == '')
      $id = $this->id;
    
        // we increment both threadcount and threadandpostcount
    //    since a thread is both a thread and a post
    $GLOBALS['db']->query(
        "update forums ".
        "set threadcount=threadcount+$increment_by, ".
            "threadandpostcount=threadandpostcount+$increment_by ".
        "where id='".$id."'"
    );
    echo    "update forums ".
        "set threadcount=threadcount+$increment_by, ".
            "threadandpostcount=threadandpostcount+$increment_by ".
        "where id='".$id."'<BR>";

  }

  function incrementPostCount($increment_by = "1", $id = '')
  {
    if($id == '')
      $id = $this->id;
    
    $GLOBALS['db']->query(
        "update forums ".
        "set threadandpostcount=threadandpostcount+$increment_by ".
        "where id='".$id."'"
    );
  }

  function decrementThreadCount($decrement_by = "1", $id = '')
  {
    if($id == '')
      $id = $this->id;
    
    // we decrement both threadcount and threadandpostcount
    //    since a thread is both a thread and a post
    $GLOBALS['db']->query(
        "update forums ".
        "set threadcount=threadcount-$decrement_by, ".
            "threadandpostcount=threadandpostcount-$decrement_by ".
        "where id='".$id."'"
    );
  }

  function decrementPostCount($decrement_by = "1", $id = '')
  {
    if($id == '')
      $id = $this->id;
    
    $GLOBALS['db']->query(
        "update forums ".
        "set threadandpostcount=threadandpostcount-$decrement_by ".
        "where id='".$id."'"
    );
  }
}
?>
