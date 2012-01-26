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
            $options[$this->primary_key] = $temp_options;
        }
        
        //If a string is passed assume it's a select statement
        if(is_string($options)){
            $temp_options = $options;
            $options = array();
            $options['select'] = $temp_options;
        }

        //If the options array contains an id (or ids) the just select those records
        if(isset($options[$this->primary_key]) AND !empty($options[$this->primary_key])){
            //Make sure the IDs are in an array
            if(!is_array($options[$this->primary_key])){
                $options[$this->primary_key] = array($options[$this->primary_key]);
            }

            $this->db->where_in($this->primary_key, $options[$this->primary_key]);
            
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

        //Return type
        if($data->num_rows() > 1){
            $return_type = "result_array";
        }
        else{
            $return_type = "row_array";
        }

        return $data->$return_type();
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

       
        if(is_array($data) AND isset($data[0]) AND is_array($data[0])){
            
            //Multiple array
            $id_array = array();
            foreach ($data as $data) {
                
                $data = $this->_remove_non_fields($data);

                //If there's required fields make sure they're present
                if(isset($this->required_fields) AND ! $this->_required_fields($data, $this->required_fields)){
                    return false;
                }

                //Do the update
                $this->db->insert($this->table_name, $data);
                $id_array[] = $this->db->insert_id();


            }

            return $id_array;
        }

        $data = $this->_remove_non_fields($data);

        //If there's required fields make sure they're present
        if(isset($this->required_fields) AND ! $this->_required_fields($data, $this->required_fields)){
            return false;
        }

        //Do the update
        $this->db->insert($this->table_name, $data);

        return $this->db->insert_id();

    }

    function delete($ids = null){

        if(!isset($ids)){
            show_error("You must pass a single <span style='font-family: courier;'>$this->primary_key</span> as a numerical value or an array of <span style='font-family: courier;'>$this->primary_key</span>'s");
        }

        //is the $ids variable a number?
        if(is_numeric($ids)){
            $id_array[] = $ids;
        }
        else{
            $id_array = $ids;
        }

        if(!is_array($id_array)){
            show_error("You must pass a single <span style='font-family: courier;'>$this->primary_key</span> as a numerical value or an array of <span style='font-family: courier;'>$this->primary_key</span>'s");
        }

        foreach ($id_array as $id) {
            $this->db->where($this->primary_key, $id);
            $this->db->delete($this->table_name);
        }

        return true;

    }

    function _remove_non_fields($data)
    {
        //remove any fields that don't exist
        foreach ($data as $key => $value) {
            if(!$this->db->field_exists($key, $this->table_name)){
                //The field doesn't exist so unset it
                unset($data[$key]);
            } 
        }

        return $data;
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