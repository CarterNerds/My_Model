<?php

class Content_generator {

    function __construct() {
        require_once 'content_generator/LoremIpsum.class.php';
        $this->content = new LoremIpsumGenerator();
        $this->CI = &get_instance();
    }

    function get_content($length = 100, $format = "html", $loremipsum = true) {
        return $this->content->getContent($length, $format, $loremipsum);
    }

    function populate_table($table = null, $rows = 10) {

        if (!$table) {
            return false;
        }

        //Does that table exist?
        if (!$this->CI->db->table_exists($table)) {
            show_error("The table: $table doesn't exist.");
            return false;
        }
        
        //Loop through the fields and create a "type"
        $fields = $this->CI->db->field_data($table);
        $data_fields = array();
        for ($i=0; $i<$rows; $i ++) {
            foreach ($fields as $field){
               //igonore the primary keys
               if(!$field->primary_key){
                   
                   //switch through field types and get content
                   //$data_fields[];
                   dump($field->type);
               }
            } 
        }
         

        //Loop through the count adding records
    }

}
