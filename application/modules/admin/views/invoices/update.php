<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$("form[name='send_invoice']").submit(function(e) {
		 
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "<?php echo base_url('admin/invoices/send_invoice'); ?>",
            type: "POST",
            data: formData,
            async: false,
            success: function (msg) {
			$('body,html').animate({ scrollTop: 0 }, 200);
            $("#sendby_ajax").html(msg); 
			$("#sendby_submitbutton").html('<button type="submit" class="btn btn-embossed btn-primary">Save</button>');
			 
			 
            
        },
            cache: false,
            contentType: false,
            processData: false
        });

        e.preventDefault();
    });
});	
function create_pdf( invoice_id )
 { 
  
    $.ajax({
        type: "GET",
        url: "<?php echo base_url('admin/invoices/ajax_create_pdf' ); ?>/" + invoice_id,
        success: function(msg)
        {
			if( msg != '' )
            {	  
                
                $("#pdf_url").attr("href", msg)
                
                var index = msg.lastIndexOf("/") + 1;
				var filename = msg.substr(index);				 
				$("#pdf_url").html(filename);
				
				$("#invoice_pdf").val(filename);
				
            }
             
        }

    });
   
    
 }	

$(document).ready(function() {
	
	//getQtemplatesProducts(<?php echo $salesorder->qtemplate_id;?>);
	
	$("form[name='update_invoice']").submit(function(e) {
        
       
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "<?php echo base_url('admin/invoices/update_process'); ?>",
            type: "POST",
            data: formData,
            async: false,
            success: function (msg) {
			$('body,html').animate({ scrollTop: 0 }, 200);
            $("#invoice_ajax").html(msg); 
			$("#invoice_submitbutton").html('<button type="submit" class="btn btn-embossed btn-primary">Save</button>');
			
			 
        },
            cache: false,
            contentType: false,
            processData: false
        });

        e.preventDefault();
    });
});
 
 
 </script>
 <script>
   
 function customer_value(customer_id)
 { 
 		   $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('admin/invoices/ajax_customer_detais').'/';?>'+customer_id,
                    data: id='customer_id',
                    dataType:'json', 
                    success: function(data){
                     
                  //  alert(data.address);   
                    $("#email").val(data.email);
                    $("#address").val(data.address);  
                },
			});
 }
  
  
 </script>
 
 <!-- BEGIN PAGE CONTENT -->
        <div class="page-content">
        <div class="header">
            <h2><strong>Invoice <?php echo $invoice->invoice_number;?></strong></h2>
            <div class="breadcrumb-wrapper">
            	<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-send_by_email" onclick="create_pdf(<?php echo $invoice->id;?>)">Send by Email</a>
            	 
               <a href="<?php echo base_url('admin/invoices/print_quot/'.$invoice->id); ?>" class="btn btn-primary" target="">Print</a>
               
               
            	
               </div>            
          </div>
           <div class="row">
           	<div class="col-md-12">
                  <div class="panel">
                     
                     <div class="panel-content">
                   		 <div class="row">
                          					&nbsp;	   
					         </div>
                   					<div id="invoice_ajax"> 
				                          <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');}?>         
				                      </div>
				         
				            <form id="update_invoice" name="update_invoice" class="form-validation" accept-charset="utf-8" enctype="multipart/form-data" method="post"> 
                        				<input  type="hidden" name="invoice_id" value="<?php echo $invoice->id;?>"/> 
                        				<div class="row">
                          					<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Customer</label>
					                              <div class="append-icon">
					                                
					                                 <select name="customer_id" class="form-control" data-search="true" onchange="customer_value(this.value);">
					                                <option value=""></option>
					                                <?php foreach( $companies as $company){ ?>
					                                <option value="<?php echo $company->id;?>" <?php if($salesorder->customer_id==$company->id){?>selected<?php }?>><?php echo $company->name;?></option>
					                                <?php }?> 
					                                </select>
					                                 
					                              </div>
					                            </div>
					                          </div>
					                          
											<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Invoice Date</label>
					                              <div class="append-icon">
					                                 
					                                <input type="text" name="invoice_date" id="invoice_date" class="date-picker form-control" value="<?php if($invoice->invoice_date){ echo date('m/d/Y',$invoice->invoice_date);}else{ echo date('m/d/Y');}?>">
					                              </div>
					                            </div>
					                          </div>			
					                        </div>
					                    <div class="row">
					                    	<div class="col-sm-6">
				                                 <div class="form-group">
				                              <label class="control-label">Email</label>
				                              <div class="append-icon">
				                                
				                               <input type="text" name="email" id="email" class="form-control" value="<?php echo $this->customers_model->get_company($salesorder->customer_id)->email; ?>" readonly>  
				                                 
				                              </div>
				                            </div>
				                              </div>
					                         <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Due Date</label>
					                              <div class="append-icon">
					                                  
					                                <input type="text" name="due_date" id="due_date" class="date-picker form-control" value="<?php if($invoice->due_date){ echo date('m/d/Y',$invoice->due_date);}else{ echo date('m/d/Y');}?>">
					                              </div>
					                            </div>
					                          </div>
					                    </div>
					                    <div class="row">
					                    	
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Address</label>
					                              <div class="append-icon">	 
					                                <textarea name="address" id="address" rows="6" class="form-control" readonly><?php echo $this->customers_model->get_company($salesorder->customer_id)->address; ?></textarea>
					                              </div>
					                            </div>
					                          </div>
											  
											   <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Payment Term</label>
					                              <div class="append-icon">	 
					                                <select name="payment_term" class="form-control"> 
					                                <option value=""></option>
					                                <option value="<?php echo config('payment_term1'); ?>" <?php if($invoice->payment_term==config('payment_term1')){?>selected<?php }?>><?php echo config('payment_term1'); ?> Days</option> 
					 
					 							<option value="<?php echo config('payment_term2'); ?>" <?php if($invoice->payment_term==config('payment_term2')){?>selected<?php }?>><?php echo config('payment_term2'); ?> Days</option> 
					 							<option value="<?php echo config('payment_term3'); ?>" <?php if($invoice->payment_term==config('payment_term3')){?>selected<?php }?>><?php echo config('payment_term3'); ?> Days</option> 
					 							
					                                <option value="0" <?php if($invoice->payment_term=="0"){?>selected<?php }?>>Immediate Payment</option>
					                                 
					                                
					                                 </select>
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Status</label>
					                              <div class="append-icon">	 
					                                <select name="status" class="form-control"> 
					                                <option value="Open Invoice" <?php if($invoice->status=="Open Invoice"){?>selected<?php }?>>Open Invoice</option>
					                                <option value="Overdue Invoice" <?php if($invoice->status=="Overdue Invoice"){?>selected<?php }?>>Overdue Invoice</option>
					                                <option value="Paid Invoice" <?php if($invoice->status=="Paid Invoice"){?>selected<?php }?>>Paid Invoice</option>
					                                 
					                                
					                                 </select>
					                              </div>
					                            </div>
					                          </div>
					                    </div>
										
										 
					                     
  										  <div class="row">
                        			     	
                        			     	<div class="panel-content">
                   									<label class="control-label">Order</label> 
                									 <table class="table">
									                    <thead>
									                      <tr style="font-size: 12px;">                         
									                        <th>Product</th>
									                        <th>Description</th>
									                        <th>Quantity</th>
									                        <th>Unit Price</th>
									                        <th>Taxes</th>
									                        <th>Subtotal</th>
									                        <th></th>
									                      </tr>
									                    </thead>
									                    <tbody id="InputsWrapper">
									                      <?php if( ! empty($qo_products) ){?>
					    									<?php foreach( $qo_products as $qo_product){ ?> 
					    								  <tr>
					    								  	<td><?php echo $qo_product->product_name;?></td>
					    								  	<td><?php echo $qo_product->discription;?></td>
					    								  	<td><?php echo $qo_product->quantity;?></td>
					    								  	<td><?php echo $qo_product->price;?></td>
					    								  	<td><?php echo number_format($qo_product->quantity*$qo_product->price*config('sales_tax')/100,2,'.',' ');?></td>
					    								  	
					    								  	<td><?php echo $qo_product->sub_total;?></td>
					    								  	
					    								  </tr>	
									                       
									                      <?php } ?>
					 									<?php } ?> 
									                       
									                    </tbody>
									                  </table>
									                  
                 									 </div>
                        			     	
                        			     </div>
                        			 
				                  <div class="row">
				                  	<div class="col-sm-8">
				                  		<div class="row">
					           		                 
										</div>
									</div>
									<div class="col-sm-4">
									    <div class="row">
					           		                 <div class="col-sm-12">
					                          	  <div class="form-group">
					                              <label class="col-sm-6 control-label">Untaxed Amount </label>
					                              <div class="col-sm-6 append-icon">
					                               <?php echo $invoice->total; ?> 
					                                
					                              </div>
					                              
					                            </div>
												</div>
												</div>
									    <div class="row">
					           		                 <div class="col-sm-12">
					                          	  <div class="form-group">
					                              <label class="col-sm-6 control-label">Taxes </label>
					                              <div class="col-sm-6 append-icon">
					                               <?php echo $invoice->tax_amount; ?> 
					                                
					                              </div>
					                              
					                            </div>
												</div>
												</div>
										<div class="row">
					           		                 <div class="col-sm-12">
					                          	  <div class="form-group">
					                              <label class="col-sm-6 control-label">Total </label>
					                              <div class="col-sm-6 append-icon">
					                               <?php echo $invoice->grand_total; ?> 
					                                
					                              </div>
					                              
					                            </div>
												</div>
												</div>	
									</div>
				                  </div>     
					                        
                        				<div class="text-left  m-t-20">
                         				 <div id="invoice_submitbutton"><button type="submit" class="btn btn-embossed btn-primary">Update</button></div>
                           
                        </div>
                      </form>             
                  			 <div class="row">
                          					&nbsp;	   
					         </div>	    
                  </div>
                  </div>
                </div>
           	</div>
            	
 		</div>   
  <!-- END PAGE CONTENT -->


<!-- START MODAL SEND BY EMAIL CONTENT -->
 <div class="modal fade" id="modal-send_by_email" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons-office-52"></i></button>
                  <h4 class="modal-title"><strong>Send </strong>by Email</h4>
                </div>
               	<div id="sendby_ajax" style="text-align:center;
"> 
				                          <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');}?>         
				  </div>
				  
               	 <div class="modal-body">
                  <form id="send_invoice" name="send_invoice" class="form-validation" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                  <input type="hidden" name="quotation_id" id="quotation_id" value="<?php echo $salesorder->id;?>" class="form-control">    
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Subject</label> 
                        	<input type="text" name="email_subject" id="email_subject" value="Demo Company Invoice (Ref <?php echo $invoice->invoice_number;?>)" class="form-control"> 
                         
                      </div>
                    </div>
					 
                     
                  </div>
                   
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Recipients</label> 
                        	<select name="recipients[]" id="recipients" class="form-control" data-search="true" multiple>
                                <option value=""></option>
                                <?php foreach( $companies as $company){ ?>
					               <option value="<?php echo $company->email;?>" <?php if($salesorder->customer_id==$company->id){?>selected<?php }?>><?php echo $company->name." (".$company->email.")";?></option>
					             
					             <?php }?> 
					       
					       </select>
                         
                      </div>
                    </div>
					 
                     
                  </div>
				  
				  <div class="row">
                    
					<div class="col-md-12">
                      <div class="form-group">
                        <label for="field-1" class="control-label"></label>
                         
                       <textarea name="message_body" id="message_body" cols="80" rows="10" class="cke-editor">
                       	
                       	<p>Hello <?php echo customer_name($salesorder->customer_id)->name; ?>,</p>

    <p>Here is your order confirmation from Demo Company: </p>

    <p style="border-left: 1px solid #8e0000; margin-left: 30px;">
       &nbsp;&nbsp;<strong>REFERENCES</strong><br>
       &nbsp;&nbsp;Invoice number: <strong><?php echo $invoice->invoice_number;?></strong><br>
       &nbsp;&nbsp;Invoice total: <strong><?php echo $invoice->grand_total; ?></strong><br>
       &nbsp;&nbsp;Invoice date: <?php echo date('m/d/Y',$invoice->invoice_date); ?> <br>
       
    </p>
                       	
                       </textarea>	 
                         
                      </div>
                    </div>
                     
                  </div>	
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="field-1" class="control-label">File</label> 
                        	<a href="" id="pdf_url" target="_blank"></a>
                         <input type="hidden" name="invoice_pdf" id="invoice_pdf" value="" class="form-control">
                      </div>
                    </div>
					 
                     
                  </div> 
                  <div class="modal-footer text-center"> 
                   <div id="sendby_submitbutton"><button type="submit" class="btn btn-embossed btn-primary">Send</button></div>
                  </div>
                 </form> 
                  
                </div>
                  
                
              </div>
            </div>
          </div>
          
           