<?php

class Posts extends MY_Model{
	
	function __construct()
	{
	    parent::__construct();

        //Set the model info
        $this->table_name = "posts";
        $this->primary_key = "post_id";
        $this->order_by = "post_id";
        $this->sort = "desc";
        
        $this->required_fields = array(
            "post_title",
            "post_body",
            "post_pubdate",
            "post_author",
            "post_slug"
        );

	}

}
