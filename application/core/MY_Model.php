<?php

class My_Model extends CI_Model{
    
    var $table_name;
    var $primary_key;
    var $order_by;
    var $sort;
    var $limit;
    var $offset;
    var $validate;
    var $required_fields;


    function __construct() {
        parent::__construct();
    }

    /**
     * Selects data based on given parameters
     * @param  int varchar array $options Options to select data
     * @return array
     */
    function get($options = null)
    {
        
        //If a numerical value is passed then assume it's the ID 
        if(is_numeric($options)){
            $temp_options = $options;
            $options = array();
            $options['id'] = $temp_options;
        }
        
        //If a string is passed assume it's a select statement
        if(is_string($options)){
            $temp_options = $options;
            $options = array();
            $options['select'] = $temp_options;
        }

        //If the options array contains an id (or ids) the just select those records
        if(isset($options['id']) AND !empty($options['id'])){
            //Make sure the IDs are in an array
            if(!is_array($options['id'])){
                $options['id'] = array($options['id']);
            }

            $this->db->where_in($this->primary_key, $options['id']);
            
        }
        
        //Select fields in the $options array if it's required
        if(isset($options['select']) AND ! empty($options['select'])){
            //Make sure the select fields are in an array
            if(!is_array($options['select'])){
                $options['select'] = array($options['select']);
            }
            $this->db->select(implode(",", $options['select']));
        }
        
        //Select fields in the $options array if it's required
        if(isset($options['where']) AND ! empty($options['where'])){
            //Where options have to be an array
            if(!is_array($options['where'])){
                show_error("Where statements must be an Array");
            }
            $this->db->where($options['where']);
        }
        
        
        //Filter Like statements
        if(isset($options['like']) AND ! empty($options['like'])){
            //Where options have to be an array
            if(!is_array($options['like'])){
                show_error("Like statements must be an Array");
            }
            foreach ($options['like'] as $key => $value) {
                $this->db->like($key, $value);        
            }
        }
        
        //Filter Or Like statements
        if(isset($options['or_like']) AND ! empty($options['or_like'])){
            //Where options have to be an array
            if(!is_array($options['or_like'])){
                show_error("Or Like statements must be an Array");
            }
            foreach ($options['or_like'] as $key => $value) {
                $this->db->or_like($key, $value);        
            }
        }
        
        
        //Set the order
        $this->db->order_by($this->order_by, $this->sort);
        
        //Has the limit been specified?
        if($this->limit){
            $this->db->limit($this->limit, $this->offset);
        }

        $data = $this->db->get($this->table_name);

        dump($this->db->last_query());

        //Return type
        $return_type = "result_array";
        if(isset($options['return_type']) AND !empty($options['return_type'])){
            $return_type = $options['return_type'];
        }



        dump($data->$return_type());

        return $data;
    }

    function save($data, $id = false)
    {
        if(!$data){
            return false;
        }

        //If the ID parameter is passed then assume that we're doing an update
        if($id != false){

            //if the primary key is in the data array then unset it
            if(in_array($this->primary_key, $data)){
                unset($data[$this->primary_key]);
            }
            //If there's required fields make sure they're present
            if(isset($this->required_fields) AND ! $this->_required_fields($data, $this->required_fields)){
                return false;
            }
            //Do the update
            $this->db->where($this->primary_key, $id);
            $this->db->update($this->table_name, $data);

            return $this->db->affected_rows();

        }

        //An insert

        //remove any fields that don't exist
        foreach ($data as $key => $value) {
            if(!$this->db->field_exists($key, $this->table_name)){
                //The field doesn't exist so unset it
                unset($data[$key]);
            } 
        }

        //If there's required fields make sure they're present
        if(isset($this->required_fields) AND ! $this->_required_fields($data, $this->required_fields)){
            return false;
        }



        //Do the update
        $this->db->insert($this->table_name, $data);

        return $this->db->insert_id();

    }


    function _required_fields($data, $required)
    {

        $req_fields = $required;
        $ok = true;
        foreach($required as $required){

            if(!isset($data[$required]) || $data[$required] == ""){
                $ok = false;
                $failed_array[] = $required;
            }
        }
        if(isset($failed_array)){
            $missing = "<ul>";
            foreach ($failed_array as $required) {
                $missing .= "<li>$required</li>";
            }
            $missing .= "</ul>"; 
            
            $passed = "<ul>";
            foreach ($data as $key => $value) {
                $passed .= "<li>['$key'] => $value</li>";
            }
            $passed .= "</ul>";
            
            $required_msg = "<ul>";
            foreach ($req_fields as $required) {
                $required_msg .= "<li>$required</li>";
            }
            $required_msg .= "</ul>";    

            show_error("<p>Not all of the required fields were passed.</p> 

            <h4>Missing:</h4><pre>$missing</pre>
            <h4>Passed:</h4><pre>$passed</pre>
            <h4>Expected:</h4><pre>$required_msg</pre>
            ");
        }
        return $ok;
    }
}