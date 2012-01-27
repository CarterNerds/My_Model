<?php

class Test extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
                $this->load->model('posts');

                
	}
	
	function index()
	{
                /*
                $options['id'] = 1;
                $options['select'] = array("post_title", "post_id");
                
                $options['where'] = array("post_title" => "This is my first post");
                $options['like'] = array("post_author" => "Some", "post_body" => "Lorem");
                $options['or_like'] = array("post_author" => "Some", "post_body" => "Lorem");
                
                $this->posts->get("post_id");
                
                $this->posts->sort = "desc";
                
                $this->posts->limit = 2;
                $this->posts->offset = 10;
        
                $options['where'] = array("post_pubdate <" => '2011-12-18');

                $this->posts->get(1);

        
                */
                $data = array();
                $data[0]["post_title"] = "Paul's 10th title!!";
                $data[0]["post_body"] = "Hello dolor sit amet, consectetur adipiscing elit. Donec varius libero dapibus justo mattis sit amet pulvinar ipsum pharetra. Fusce tempor felis at quam laoreet fringilla. Vivamus eros nulla, tempor vitae tempus vel, bibendum eget ante. Nunc mattis, quam nec luctus fringilla, sem mauris tincidunt risus, id convallis enim lacus vulputate arcu. Praesent placerat facilisis dolor, eu congue ipsum viverra in. Suspendisse potenti. Vivamus sollicitudin, velit ut placerat fermentum, mi sem pretium nisl, sed fermentum sapien lectus a sem. Sed interdum arcu in felis aliquam ac mollis eros euismod. Praesent lorem odio, ultrices et venenatis eu, faucibus nec lorem.";
                $data[0]["post_pubdate"] = "2011-10-17 16:57:48";
                $data[0]["post_author"] ="Joost van Veen";
                $data[0]["post_slug"] = "fourth-post";

                $data[1]["post_title"] = "Paul's 11th title!!";
                $data[1]["post_body"] = "Hello dolor sit amet, consectetur adipiscing elit. Donec varius libero dapibus justo mattis sit amet pulvinar ipsum pharetra. Fusce tempor felis at quam laoreet fringilla. Vivamus eros nulla, tempor vitae tempus vel, bibendum eget ante. Nunc mattis, quam nec luctus fringilla, sem mauris tincidunt risus, id convallis enim lacus vulputate arcu. Praesent placerat facilisis dolor, eu congue ipsum viverra in. Suspendisse potenti. Vivamus sollicitudin, velit ut placerat fermentum, mi sem pretium nisl, sed fermentum sapien lectus a sem. Sed interdum arcu in felis aliquam ac mollis eros euismod. Praesent lorem odio, ultrices et venenatis eu, faucibus nec lorem.";
                $data[1]["post_pubdate"] = "2011-10-17 16:57:48";
                $data[1]["post_author"] ="Joost van Veen";
                $data[1]["post_slug"] = "fourth-post";
                $data[1]["post_sldddug"] = "fourth-post";

                /*
                $data = array();
                $data["post_title"] = "Paul's 10th title!!";
                $data["post_body"] = "Hello dolor sit amet, consectetur adipiscing elit. Donec varius libero dapibus justo mattis sit amet pulvinar ipsum pharetra. Fusce tempor felis at quam laoreet fringilla. Vivamus eros nulla, tempor vitae tempus vel, bibendum eget ante. Nunc mattis, quam nec luctus fringilla, sem mauris tincidunt risus, id convallis enim lacus vulputate arcu. Praesent placerat facilisis dolor, eu congue ipsum viverra in. Suspendisse potenti. Vivamus sollicitudin, velit ut placerat fermentum, mi sem pretium nisl, sed fermentum sapien lectus a sem. Sed interdum arcu in felis aliquam ac mollis eros euismod. Praesent lorem odio, ultrices et venenatis eu, faucibus nec lorem.";
                $data["post_pubdate"] = "2011-10-17 16:57:48";
                $data["post_author"] ="Joost van Veen";
                $data["post_slug"] = "fourth-post";
                */
                
                //$id = $this->posts->save($data);
                
                $this->posts->delete(3);

                //dump($id);
                $this->posts->sort = "asc";
                $options['post_id'] = array(18,2);
                $options['key_value'] = array("post_title", "post_author");
                $data = $this->posts->get($options);
                dump($data);


	}

}