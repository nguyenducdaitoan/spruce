<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$("form[name='send_quotation']").submit(function(e) {
		 
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "<?php echo base_url('admin/quotations/send_quotation'); ?>",
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
function create_pdf( quotation_id )
 { 
  
    $.ajax({
        type: "GET",
        url: "<?php echo base_url('admin/quotations/ajax_create_pdf' ); ?>/" + quotation_id,
        success: function(msg)
        {
			if( msg != '' )
            {	  
                
                $("#pdf_url").attr("href", msg)
                
                var index = msg.lastIndexOf("/") + 1;
				var filename = msg.substr(index);				 
				$("#pdf_url").html(filename);
				
				$("#quotation_pdf").val(filename);
				
            }
             
        }

    });
   
    
 }	

$(document).ready(function() {
	
	//getQtemplatesProducts(<?php echo $quotation->qtemplate_id;?>);
	
	$("form[name='update_quotation']").submit(function(e) {
        
        update_total_price();
        
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "<?php echo base_url('admin/quotations/update_process'); ?>",
            type: "POST",
            data: formData,
            async: false,
            success: function (msg) {
			$('body,html').animate({ scrollTop: 0 }, 200);
            $("#quotation_ajax").html(msg); 
			$("#quotation_submitbutton").html('<button type="submit" class="btn btn-embossed btn-primary">Save</button>');
			
			//$('.remove_tr').remove(); //remove tr
			
			 
            
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
   
   function product_price_changes(quantity,product_price,sub_total_id)
 {
 	
 	var no_quantity=$("#"+quantity).val();
 	 
 	var sub_total= parseFloat(no_quantity * product_price).toFixed(2); 
 	
 	var tax_amount = 0;		
		tax_amount = (sub_total*<?php echo config('sales_tax'); ?>) / 100;
		$('#taxes').val(tax_amount.toFixed(2));
 	 
 	$('#'+sub_total_id).val(sub_total); 
 	 
 }
 
 function update_total_price()
 {
 	var sub_total = 0;
 	$('input[name^="sub_total"]').each(function() {
   	 sub_total += parseFloat($(this).val());     
     $('#total').val(sub_total.toFixed(2)); 
     
        var tax_per=<?php echo config('sales_tax'); ?>;
		var tax_amount = 0;
		
		tax_amount = (sub_total*tax_per) / 100;
		$('#tax_amount').val(tax_amount.toFixed(2)); 
		
		var grand_total = 0;
		grand_total = sub_total + tax_amount;
		$('#grand_total').val(grand_total.toFixed(2)); 
     
	});
	 
 }
 
 function product_value()
 {
 	var all_Val=$("#product_list").val();
 	var res = all_Val.split("_");
 	
 	$('#product_id').val(res[0]);  
 	$('#product_name1').val(res[1]);
 	$('#unit_price').val(res[2]);
 	$('#pdescription').val(res[3]); 
 }
 
								 $(document).ready(function() {
						 	 	
								
								
								var MaxInputs       = 50; //maximum input boxes allowed
								var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
								var AddButton       = $("#AddMoreFileBox"); //Add button ID
								
								var x = InputsWrapper.length; //initlal text box count
								var FieldCount=1 + <?php echo count($products);?>; //to keep track of text box added
								
								 
								//$("#total").val("0");
								
								$(AddButton).click(function (e)  //on add input button click
								{
									 
									var product_id=$("#product_id").val();
									
									if(product_id=='')
									{
										$("#call_ajax").html('<div class="alert alert-danger">Select Product</div>'); 
										return false;
									}
									else{
									
									var new_product_price=getProducts_Price(product_id); 
									var product_name=$("#product_name1").val();
									var product_quantity=$("#product_quantity").val();
									var product_price=$("#unit_price").val();
									if(new_product_price!=="")
									{
										product_price=parseFloat(new_product_price).toFixed(2);
									}
									else
									{
										product_price=parseFloat(product_price).toFixed(2);
									}
									var description=$("#pdescription").val();
									var tax_rat = <?php echo config('sales_tax');?>
									
									var sub_total= parseFloat(product_quantity * product_price).toFixed(2);
									var main_total=$("#total").val();
									
												if(x <= MaxInputs) //max input box allowed
												{
														FieldCount++; //text box added increment
														 
														$(InputsWrapper).append('<tr class="remove_tr"><td><input type="hidden" name="p_id[]" id="p_id" value="'+product_id+'" readOnly><input type="text" name="product_name[]" id="product_name" value="'+product_name+'" class="form-control" readOnly></td><td><textarea name=description[]" id="description" rows="2" class="form-control" readOnly>'+description+'</textarea></td><td><input type="text" name="quantity[]" id="quantity'+FieldCount+'" value="'+product_quantity+'" class="form-control" onchange="product_price_changes(\'quantity'+FieldCount+'\','+product_price+',\'sub_total'+FieldCount+'\');"></td><td><input type="text" name="product_price[]" id="product_price" value="'+product_price+'" class="form-control" readOnly></td><td><input type="text" name="taxes[]" id="taxes" value="'+parseFloat((product_quantity*product_price*tax_rat)/100).toFixed(2)+'" class="form-control" readOnly></td><td><input type="text" name="sub_total[]" id="sub_total'+FieldCount+'" value="'+sub_total+'" class="form-control" readOnly></td><td><a href="javascript:void(0)" class="delete btn btn-sm btn-danger removeclass" data-toggle="modal" data-target="#modal-basic"><i class="icons-office-52"></i></a></td></tr>');
														
														
														x++; //text box increment
												}
												
												//Total price count 
												var total1 = parseInt(sub_total) + parseInt(main_total) ;
												$('#total').val(total1.toFixed(2)); 
												var tax_per=<?php echo config('sales_tax'); ?>;
									 			var tax_amount = 0;
									 			
									 			tax_amount = (total1*tax_per) / 100;
									 			$('#tax_amount').val(tax_amount.toFixed(2)); 
									 			var grand_total = 0;
									 			grand_total = total1 + tax_amount;
									 			$('#grand_total').val(grand_total.toFixed(2)); 
									 
											$("#call_ajax").html('<div class="alert alert-success">Added Successful</div>');
											return false;
										
										}
								});
								
								
								
								$("body").on("click",".removeclass", function(e){ //user click on remove text
												if( x > 1 ) {
																$(this).parent().parent().remove(); //remove text box
																x--; //decrement textbox
												}
								return false;
								}) 
								
								});

	


//Delete products

function delete_product( product_id )
 { 
  
    $.ajax({

        type: "GET",
        url: "<?php echo base_url('admin/qtemplates/delete_product' ); ?>/" + product_id,
        success: function(msg)
        {
			if( msg != '' )
            {	 
                $('#qproduct_id_' + product_id).remove();
                $('#total').val(msg.toFixed(2)); 
            }
             
        }

    });
   
    
 }
 
 function delete_qo_product( product_id )
 { 
  
    $.ajax({

        type: "GET",
        url: "<?php echo base_url('admin/quotations/delete_qo_product' ); ?>/" + product_id,
        success: function(msg)
        {
			if( msg != '' )
            {	 
                $('#qo_product_id_' + product_id).remove();
                update_total_price();
            }
             
        }

    });
   
    
 }

//Get quotation templates products
function getQtemplatesProducts(id)
{
                //alert('this id value :'+id);
                var pricelist_id=document.getElementById("pricelist_id").value;
                //alert(pricelist_id);
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('admin/quotations/ajax_qtemplates_products').'/';?>'+id+'/'+pricelist_id,
                   // data: id='qt_id',
                    success: function(data){
                        //alert(data);
                        $("#InputsWrapper").html(data);
						update_total_price(); 
                },
			});
			
			 $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('admin/quotations/ajax_get_quotation_template_duration').'/';?>'+id,                    
                    success: function(data1){
                          
                        $("#expiration_date").val(data1); 
                },
			});
}

//Get quotation templates products
function getPricelistProducts(id)
{
                //alert('this id value :'+id);
                var qtemplate_id=document.getElementById("quotation_template").value;
               
               if(qtemplate_id!="")
               { 
	                $.ajax({
	                    type: "POST",
	                    url: '<?php echo base_url('admin/quotations/ajax_qtemplates_products').'/';?>'+qtemplate_id+'/'+id,
	                    data: id='qt_id',
	                    success: function(data){
	                        //alert(data);
	                        $("#InputsWrapper").html(data);
							update_total_price(); 
	               		 },
					}); 
			   }
}

function getProducts_Price(id)
{ 
			    var pricelist_id=document.getElementById("pricelist_id").value;
            	var result="";   
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url('admin/quotations/ajax_get_products_price').'/';?>'+id+'/'+pricelist_id,
                    async: false,
                    data: id='p_id',
                    success: function(data){ 
						result = data; 	 
                	}
				});
			return result; 
}	
 </script>
 
 <!-- BEGIN PAGE CONTENT -->
        <div class="page-content">
        <div class="header">
            <h2><strong>Quotation <?php echo $quotation->quotations_number;?></strong></h2>
            <div class="breadcrumb-wrapper">
            	<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-send_by_email" onclick="create_pdf(<?php echo $quotation->id;?>)">Send by Email</a>
            	 
               <a href="<?php echo base_url('admin/quotations/print_quot/'.$quotation->id); ?>" class="btn btn-primary" target="">Print</a>
               
               <a href="<?php echo base_url('admin/quotations/confirm_sale/'.$quotation->id); ?>" class="btn btn-primary" target="">Confirm Sale</a>
            	
               </div>            
          </div>
           <div class="row">
           	<div class="col-md-12">
                  <div class="panel">
                     
                     <div class="panel-content">
                   		 <div class="row">
                          					&nbsp;	   
					         </div>
                   					<div id="quotation_ajax"> 
				                          <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');}?>         
				                      </div>
				         
				            <form id="update_quotation" name="update_quotation" class="form-validation" accept-charset="utf-8" enctype="multipart/form-data" method="post"> 
                        				<input  type="hidden" name="quotation_id" value="<?php echo $quotation->id;?>"/> 
                        				<div class="row">
                          					<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Customer</label>
					                              <div class="append-icon">
					                                
					                                 <select name="customer_id" class="form-control" data-search="true">
					                                <option value=""></option>
					                                <?php foreach( $companies as $company){ ?>
					                                <option value="<?php echo $company->id;?>" <?php if($quotation->customer_id==$company->id){?>selected<?php }?>><?php echo $company->name;?></option>
					                                <?php }?> 
					                                </select>
					                                 
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
				                                 <div class="form-group">
				                              <label class="control-label">Quotation Template</label>
				                              <div class="append-icon">
				                                
				                                <select name="quotation_template" id="quotation_template" class="form-control" data-search="true" onChange="getQtemplatesProducts(this.value)">
					                                <option value=""></option>
					                                <?php foreach( $qtemplates as $qtemplate){ ?>
					                                <option value="<?php echo $qtemplate->id;?>" <?php if($quotation->qtemplate_id==$qtemplate->id){?>selected<?php }?>><?php echo $qtemplate->quotation_template;?></option>
					                                <?php }?> 
					                                </select>
				                                 
				                              </div>
				                            </div>
				                              </div>
					                        </div>
					                    <div class="row">
					                    	<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Date</label>
					                              <div class="append-icon">
					                                 
					                                <input type="text" name="date" id="date" class="datetimepicker form-control" value="<?php echo date('m/d/Y g:i',$quotation->date);?>">
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Pricelist</label>
					                              <div class="append-icon">
					                                 
					                              <select name="pricelist_id" id="pricelist_id" class="form-control" data-search="true" onChange="getPricelistProducts(this.value)">
					                                <option value=""></option>
					                                <?php foreach( $pricelists as $pricelist){ ?>
					                                <option value="<?php echo $pricelist->id;?>" <?php if($quotation->pricelist_id==$pricelist->id){?>selected<?php }?>><?php echo $pricelist->pricelist_name.' ('.$pricelist->pricelist_currency.')';?></option>
					                                <?php }?> 
					                                </select>
					                              </div>
					                            </div>
					                          </div>
					                    </div>
					                    <div class="row">
					                    	<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Expiration Date</label>
					                              <div class="append-icon">
					                                  
					                                <input type="text" name="expiration_date" id="expiration_date" class="date-picker form-control" value="<?php echo date('m/d/Y',$quotation->exp_date);?>">
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Payment Term</label>
					                              <div class="append-icon">	 
					                                <select name="payment_term" class="form-control">
					                                <option value=""></option>
					                                <option value="<?php echo config('payment_term1'); ?>" <?php if($quotation->payment_term==config('payment_term1')){?>selected<?php }?>><?php echo config('payment_term1'); ?> Days</option> 
					 
					 							<option value="<?php echo config('payment_term2'); ?>" <?php if($quotation->payment_term==config('payment_term2')){?>selected<?php }?>><?php echo config('payment_term2'); ?> Days</option> 
					 							<option value="<?php echo config('payment_term3'); ?>" <?php if($quotation->payment_term==config('payment_term3')){?>selected<?php }?>><?php echo config('payment_term3'); ?> Days</option> 
					 							
					                                <option value="0" <?php if($quotation->payment_term=="0"){?>selected<?php }?>>Immediate Payment</option>
					                                
					                                 </select>
					                              </div>
					                            </div>
					                          </div>
					                    </div>
										<div class="row">
					                    	<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Salesperson</label>
					                              <div class="append-icon">
					                                 
					                                <select name="sales_person" class="form-control" data-search="true">
					                                <option value=""></option>
					                                <?php foreach( $staffs as $staff){ ?>
					                                <option value="<?php echo $staff->id;?>" <?php if($quotation->sales_person==$staff->id){?>selected<?php }?>><?php echo $staff->first_name.' '. $staff->last_name;?></option>
					                                <?php }?> 
					                                </select>
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Status</label>
					                              <div class="append-icon">	 
					                                <select name="status" class="form-control"> 
					                                <option value="Draft Quotation" <?php if($quotation->status=="Draft Quotation"){?>selected<?php }?>>Draft Quotation</option>
					                                <option value="Quotation Sent" <?php if($quotation->status=="Quotation Sent"){?>selected<?php }?>>Quotation Sent</option>
					                                 
					                                
					                                 </select>
					                              </div>
					                            </div>
					                          </div>
					                    </div>
										<div class="row">
											<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Sales Team</label>
					                              <div class="append-icon">
					                               <select name="sales_team_id" id="sales_team_id" class="form-control" data-search="true">
					                                <option value="" selected="selected"></option>
					                                <?php foreach( $salesteams as $salesteam){ ?>
					                                <option value="<?php echo $salesteam->id;?>" <?php if($quotation->sales_team_id==$salesteam->id){?> selected="selected"<?php }?>><?php echo $salesteam->salesteam;?></option>
					                                <?php }?> 
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
					    									<?php foreach( $qo_products as $qo_product){ 
					    									
					    									?> 
									                      <tr class="remove_tr" id="qo_product_id_<?php echo $qo_product->id;?>"><td>
									                      <input type="hidden" name="quotation_product_id[]" id="quotation_product_id" value="<?php echo $qo_product->id;?>" />
									                      <input type="hidden" name="p_id[]" id="p_id" value="<?php echo $qo_product->product_id;?>" readOnly><input type="text" name="product_name[]" id="product_name" value="<?php echo $qo_product->product_name;?>" class="form-control" readOnly></td><td><textarea name=description[]" id="description" rows="2" class="form-control" readOnly><?php echo $qo_product->discription;?></textarea></td><td><input type="text" name="quantity[]" id="quantity<?php echo $qo_product->product_id;?>" value="<?php echo $qo_product->quantity;?>" class="form-control" onchange="product_price_changes('quantity<?php echo $qo_product->product_id;?>','<?php echo $qo_product->price;?>','sub_total<?php echo $qo_product->product_id;?>');"></td><td><input type="text" name="product_price[]" id="product_price" value="<?php echo $qo_product->price;?>" class="form-control" readOnly></td><td><input type="text" name="taxes[]" id="taxes<?php echo $qo_product->product_id;?>" value="<?php echo number_format($qo_product->quantity*$qo_product->price*config('sales_tax')/100,2,'.',' ');?>" class="form-control" readonly></td><td><input type="text" name="sub_total[]" id="sub_total<?php echo $qo_product->product_id;?>" value="<?php echo $qo_product->sub_total;?>" class="form-control" readOnly></td><td><a href="javascript:void(0)" class="delete btn btn-sm btn-danger" onclick="delete_qo_product(<?php echo $qo_product->id;?>)"><i class="icons-office-52"></i></a></td></tr>
									                      <?php } ?>
					 									<?php } ?> 
									                       
									                    </tbody>
									                    
									                  </table>
									                  <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-create_product">Add an product</a>
                 									 </div>
					                    	
					                    </div>   
					                    <div class="row">
					                    	<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label"></label>
					                              <div class="append-icon"> 
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Untaxed Amount</label>
					                              <div class="append-icon">
					                                 
					                                <input type="text" name="total" id="total" value="<?php echo $quotation->total;?>" class="form-control" readonly/> 
					                              </div>
					                            </div>
					                          </div>
					                          
					                    </div>
					                    <div class="row">
					                    	<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label"></label>
					                              <div class="append-icon"> 
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Taxes</label>
					                              <div class="append-icon">
					                                 
					                                <input type="text" name="tax_amount" id="tax_amount" value="<?php echo $quotation->tax_amount;?>" class="form-control" readonly/> 
					                              </div>
					                            </div>
					                          </div>
					                          
					                    </div>
					                    <div class="row">
					                    	<div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Terms and Conditions</label>
					                              <div class="append-icon">
					                                 
					                                <textarea name="terms_and_conditions" rows="4" class="form-control"><?php echo $quotation->terms_and_conditions;?></textarea> 
					                              </div>
					                            </div>
					                          </div>
					                          <div class="col-sm-6">
					                            <div class="form-group">
					                              <label class="control-label">Total (<a href="javascript:void(0);" id="update_total" onclick="update_total_price();"><b>Update</b></a>)</label>
					                              <div class="append-icon">
					                                 
					                                <input type="text" name="grand_total" id="grand_total" value="<?php echo $quotation->grand_total;?>" class="form-control" readonly/> 
					                              </div>
					                            </div>
					                          </div>
					                          
					                    </div>     
					                        
                        				<div class="text-left  m-t-20">
                         				 <div id="quotation_submitbutton"><button type="submit" class="btn btn-embossed btn-primary">Update</button></div>
                           
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
 
<!-- START MODAL PRODUCT CONTENT -->
 <div class="modal fade" id="modal-create_product" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icons-office-52"></i></button>
                  <h4 class="modal-title"><strong>Product</strong> Order</h4>
                </div>
               	<div id="call_ajax"> 
				                          <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');}?>         
				  </div>
				         
				
               	  
               	 <div class="modal-body">
                   
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Product</label>
                         <input type="hidden" name="product_id" id="product_id" value="">
                         <input type="hidden" name="product_name1" id="product_name1" value="">
                        	<select name="product_list" id="product_list" class="form-control" data-search="true" onchange="product_value();">
                                <option value=""></option>
                                <?php foreach( $products as $product){ ?>
                                <option value="<?php echo $product->id.'_'.$product->product_name.'_'.$product->sale_price.'_'.$product->description_for_quotations;?>"><?php echo $product->product_name;?></option>
                                <?php }?> 
					       
					       </select>
                         
                      </div>
                    </div>
					<div class="col-md-6">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Quantity</label>
                         
                        <input type="text" name="product_quantity" id="product_quantity" value="1" class="form-control">	 
                         
                      </div>
                    </div>
                     
                  </div>
				  
				  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Unit Price</label>
                         
                        <input type="text" name="unit_price" id="unit_price" value="" class="form-control" readonly>	 
                          
                      </div>
                    </div>
					<div class="col-md-6">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Description</label>
                         
                       <textarea name="pdescription" id="pdescription" rows="2" class="form-control" readonly></textarea>	 
                         
                      </div>
                    </div>
                     
                  </div>	
                   
                   
                </div>
                 
                  <div class="modal-footer text-center"> 
                  <a href="#" id="AddMoreFileBox"><button type="button" class="btn btn-embossed btn-primary" data-dismiss="modal" onclick="">Add an item</button></a>
                  </div>
                 
                 
                
              </div>
            </div>
          </div>

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
                  <form id="send_quotation" name="send_quotation" class="form-validation" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                  <input type="hidden" name="quotation_id" id="quotation_id" value="<?php echo $quotation->id;?>" class="form-control">    
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="field-1" class="control-label">Subject</label> 
                        	<input type="text" name="email_subject" id="email_subject" value="Demo Company Order (Ref <?php echo $quotation->quotations_number;?>)" class="form-control"> 
                         
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
					               <option value="<?php echo $company->email;?>" <?php if($quotation->customer_id==$company->id){?>selected<?php }?>><?php echo $company->name." (".$company->email.")";?></option>
					             
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
                       	
                       	<p>Hello <?php echo customer_name($quotation->customer_id)->name; ?>,</p>

    <p>Here is your order confirmation from Demo Company: </p>

    <p style="border-left: 1px solid #8e0000; margin-left: 30px;">
       &nbsp;&nbsp;<strong>REFERENCES</strong><br>
       &nbsp;&nbsp;Order number: <strong><?php echo $quotation->quotations_number;?></strong><br>
       &nbsp;&nbsp;Order total: <strong><?php echo $quotation->grand_total; ?></strong><br>
       &nbsp;&nbsp;Order date: <?php echo date('m/d/Y H:i',$quotation->date); ?> <br>
       
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
                         <input type="hidden" name="quotation_pdf" id="quotation_pdf" value="" class="form-control">
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