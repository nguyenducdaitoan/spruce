<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opportunities extends CI_Controller {

    function Opportunities() 
    {
         parent::__construct();
		 $this->load->database();
		 $this->load->model("opportunities_model");
		 $this->load->model("customers_model");
		 $this->load->model("staff_model");
		 $this->load->model("salesteams_model");
		 $this->load->model("calls_model");
		 $this->load->model("meetings_model");  
		
         $this->load->library('form_validation');
         
         /*cache control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
         
         check_login(); 
    }

	function index()
	{
			//checking permission for staff
			if (!check_staff_permission('opportunities_read'))	
			{
				redirect(base_url('admin/access_denied'), 'refresh');  
			} 
			
		    	$data['opportunities'] = $this->opportunities_model->opportunities_list(userdata('id'));
		    			    	 
				$this->load->view('header');
				$this->load->view('opportunities/index',$data);
				$this->load->view('footer');
			 
	}
	function add()
	{
				//checking permission for staff
				 if (!check_staff_permission('opportunities_write'))	
				{
					redirect(base_url('admin/access_denied'), 'refresh');  
				}
			
		    	$data['companies'] = $this->customers_model->company_list();
		    	
		    	$data['staffs'] = $this->staff_model->staff_list(); 
		    	
		    	$data['salesteams'] = $this->salesteams_model->salesteams_list(); 
		    	
				$this->load->view('header');
				$this->load->view('opportunities/add',$data);
				$this->load->view('footer');
			 
	}
	function add_process()
	{
				//checking permission for staff
				  if (!check_staff_permission('opportunities_write'))	
				{
					redirect(base_url('admin/access_denied'), 'refresh');  
				}   
				 
				$this->form_validation->set_rules('opportunity', 'Opportunity', 'required');
				$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|htmlspecialchars|max_length[50]|valid_email');
				
				$this->form_validation->set_rules('customer', 'Customer', 'required'); 
	
				$this->form_validation->set_rules('salesperson_id', 'Sales Person', 'required');  			
				$this->form_validation->set_rules('sales_team_id', 'Sales Team', 'required');				
				$this->form_validation->set_rules('next_action', 'Next Action Date', 'required'); 
				$this->form_validation->set_rules('expected_closing', 'Expected Closing', 'required'); 
				
				if( $this->form_validation->run() == FALSE )
		        {
		            echo '<div class="alert"><ul>' . validation_errors('<li style="color:red">','</li>') . '</ul></div>';
		        }
		        elseif( $this->opportunities_model->exists_email( $this->input->post('email') ) > 0)
		        {
		            echo '<div class="alert alert-danger">Email already used.</div>';
		        }
		        else
		        {
		            
		            if( $this->opportunities_model->add_opportunities())
		            { 
		            
		            	$opportunity_id=$this->db->insert_id();
              
             			 add_notifications($this->input->post('salesperson_id'),'New Opportunities Added',$opportunity_id,'opportunities');
              	
		                echo 'yes_'.$opportunity_id;
		                //echo '<div class="alert ok">'.$this->lang->line('create_succesful').'</div>';
		            }
		            else
		            {
		                echo $this->lang->line('technical_problem');
		            }
		        }
			 
	}
	
	function view($opportunity_id)
	{
				//checking permission for staff
				 if (!check_staff_permission('opportunities_read'))	
				{
					redirect(base_url('admin/access_denied'), 'refresh');  
				}
				
				 
				
				$data['companies'] = $this->customers_model->company_list();
				
				$data['staffs'] = $this->staff_model->staff_list(); 
		    	 
		    	$data['calls'] = $this->calls_model->calls_list($opportunity_id,$type='opportunities');  
	
				$data['meetings'] = $this->meetings_model->meetings_list($opportunity_id,$type='opportunities');  
					  
		    	  
				$data['opportunity'] = $this->opportunities_model->get_opportunities( $opportunity_id );	 
		    	 
				$this->load->view('header');
				$this->load->view('opportunities/view',$data);
				$this->load->view('footer');
			 
	}
	
	function update($opportunity_id)
	{
			//checking permission for staff
				if (!check_staff_permission('opportunities_write'))	
				{
					redirect(base_url('admin/access_denied'), 'refresh');  
				} 
				 
				
				$data['companies'] = $this->customers_model->company_list();
				
				$data['staffs'] = $this->staff_model->staff_list(); 
		    	
		    	$data['salesteams'] = $this->salesteams_model->salesteams_list(); 
		    	 
		    	$data['calls'] = $this->calls_model->calls_list($opportunity_id,$type='opportunities');  
	
				$data['meetings'] = $this->meetings_model->meetings_list($opportunity_id,$type='opportunities');  
					    	 
		    	$data['opportunity'] = $this->opportunities_model->get_opportunities( $opportunity_id );	 
		    	 
				$this->load->view('header');
				$this->load->view('opportunities/update',$data);
			    $this->load->view('footer');
			 
	}
	
	function update_process()
	{
		//checking permission for staff
		if (!check_staff_permission('opportunities_write'))	
			{
				redirect(base_url('admin/access_denied'), 'refresh');  
			}		   
		
		$this->form_validation->set_rules('opportunity', 'Opportunity', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|htmlspecialchars|max_length[50]|valid_email');
		$this->form_validation->set_rules('customer', 'Customer', 'required'); 
		$this->form_validation->set_rules('salesperson_id', 'Sales Person', 'required');
		$this->form_validation->set_rules('sales_team_id', 'Sales Team', 'required');  
		$this->form_validation->set_rules('next_action', 'Next Action Date', 'required'); 
		$this->form_validation->set_rules('expected_closing', 'Expected Closing', 'required'); 
		
		if( $this->form_validation->run() == FALSE )
        {
            echo '<div class="alert error"><ul>' . validation_errors('<li style="color:red">','</li>') . '</ul></div>';
        }
        else
        {
            
            if( $this->opportunities_model->update_opportunities() )
            {
                echo '<div class="alert alert-success">'.$this->lang->line('update_succesful').'</div>';
            }
            else
            {
                echo $this->lang->line('technical_problem');
            }
        }
	}
	
	/*
     * deletes opportunity     *  
     */
	function delete( $opportunity_id )
	{
		//checking permission for staff
		if (!check_staff_permission('opportunities_delete'))	
			{
				redirect(base_url('admin/access_denied'), 'refresh');  
			}		    
		 
			if( $this->opportunities_model->delete($opportunity_id) )
			{
				echo 'deleted';
			}
		
	}	
	
	//Add Call
   	function add_call()
	{
		    	  
		check_login(); 
		
		$this->form_validation->set_rules('call_summary', 'Call Summary', 'required');
		 
		
		if( $this->form_validation->run() == FALSE )
        {
            echo '<div style="color:red;margin-left:15px;">' . validation_errors() . '</div>';
        }
        else
        {
            
            if( $this->calls_model->add_calls())
            { 
                echo '<div class="alert alert-success">'.$this->lang->line('create_succesful').'</div>';
            }
            else
            {
                echo $this->lang->line('technical_problem');
            }
        }
			 
	}
	
	/*
     * deletes call     *  
     */
	function call_delete( $call_id )
	{
		check_login();  
		 
			if( $this->calls_model->delete($call_id) )
			{
				echo 'deleted';
			}
		
	}
	
	
	//Add Meetings
   	function add_meeting()
	{
		    	  
		  
		$this->form_validation->set_rules('meeting_subject', 'Meeting Subject', 'required');
		$this->form_validation->set_rules('starting_date', 'Starting date', 'required');		
		$this->form_validation->set_rules('ending_date', 'Ending date', 'required');
		
		$startDate = strtotime($_POST['starting_date']);
		$endDate = strtotime($_POST['ending_date']);

		  
	 
		if( $this->form_validation->run() == FALSE )
        {
            echo '<div style="color:red;margin-left:15px;">' . validation_errors() . '</div>';
        }
        elseif ($startDate >= $endDate)
        {
			echo '<div style="color:red;margin-left:15px;">Should be greater than Start Date</div>';
		  	exit;	
		}
        else
        {
            
            if( $this->meetings_model->add_meetings())
            { 
                echo '<div class="alert alert-success">'.$this->lang->line('create_succesful').'</div>';
            }
            else
            {
                echo $this->lang->line('technical_problem');
            }
        }
			 
	} 
	
	/*
     * deletes Meetings     *  
     */
	function meeting_delete( $meeting_id )
	{
		check_login();  
		 
			if( $this->meetings_model->delete($meeting_id) )
			{
				echo 'deleted';
			}
		
	}
	
	function edit_meeting($meeting_id)
    {	
    	$data['companies'] = $this->customers_model->company_list();
				
		$data['staffs'] = $this->staff_model->staff_list(); 
		    	
		$data['salesteams'] = $this->salesteams_model->salesteams_list(); 
    
    	$data['meeting'] = $this->meetings_model->get_meeting( $meeting_id );	    	 
     
     	 
     	 
     	$this->load->view('header');
		$this->load->view('opportunities/edit_meeting',$data);
	    $this->load->view('footer');
        
    	
   	}
   	
   	function edit_meeting_process()
    {
    
    	
    	$this->form_validation->set_rules('meeting_subject', 'Meeting Subject', 'required');
		$this->form_validation->set_rules('starting_date', 'Starting date', 'required');		
		$this->form_validation->set_rules('ending_date', 'Ending date', 'required');
		
		$startDate = strtotime($_POST['starting_date']);
		$endDate = strtotime($_POST['ending_date']);

		  
		
		if( $this->form_validation->run() == FALSE )
        {
            echo '<div style="color:red;margin-left:15px;">' . validation_errors() . '</div>';
        }
        elseif ($startDate >= $endDate)
        {
			echo '<div style="color:red;margin-left:15px;">Should be greater than Start Date</div>';
		  	exit;	
		}
        else
        {
            
            if( $this->meetings_model->edit_meetings())
            { 
                echo '<div class="alert alert-success">'.$this->lang->line('update_succesful').'</div>';
            }
            else
            {
                echo $this->lang->line('technical_problem');
            }
        }
    	
    }
    
    
    function edit_call($call_id)
    {	
    	$data['companies'] = $this->customers_model->company_list();
				
		$data['staffs'] = $this->staff_model->staff_list(); 
	
    	$data['call'] = $this->calls_model->get_call( $call_id );	    	 
     
     	 
     	 
     	$this->load->view('header');
		$this->load->view('opportunities/edit_call',$data);
	    $this->load->view('footer');
        
    	
   	}
   	
   	function edit_call_process()
    {
    	
    	$this->form_validation->set_rules('call_summary', 'Call Summary', 'required');
		 
		
		if( $this->form_validation->run() == FALSE )
        {
            echo '<div style="color:red;margin-left:15px;">' . validation_errors() . '</div>';
        }
        else
        {
            
            if( $this->calls_model->edit_calls())
            { 
                echo '<div class="alert alert-success">'.$this->lang->line('update_succesful').'</div>';
            }
            else
            {
                echo $this->lang->line('technical_problem');
            }
        }
    	
    }
   	
   	/*
     * confirm sale*  
     */
	function convert_to_quotation( $opportunity_id )
	{
		if( $this->opportunities_model->convert_to_quotation($opportunity_id))
		{
			$quotation_id = $this->db->insert_id();
			redirect('admin/quotations/update/'.$quotation_id);
		}
		   
	} 
	 
}
