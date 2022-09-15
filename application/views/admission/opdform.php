    
   
            <?php if($this->session->flashdata('patientrecord_success')): ?>
          
          <?php echo "<div style='#1cc88a' class='alert alert-success alert-dismissible text-center'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h6><i class='con fa fa-check'></i></h6>".$this->session->flashdata('patientrecord_success') ."</div>" ?>
          
           <?php endif; ?>

       <?php if($this->session->flashdata('patientrecord_failed')): ?>
          
          <?php echo "<div style='bg-color:#e74a3b;' class='alert alert-success alert-dismissible text-center'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                ".$this->session->flashdata('patientrecord_failed') ."<h6><i class='con fa fa-check'></i></h6></div>" ?>
          
           <?php endif; ?>
  

     <div class="container-fluid">

         <h1 class="h3 mb-2 text-gray-800 text-center">Add Patient Record</h1>

              <hr>
                 <div class="row "><!-- ROW 1 -->
                   <div class="col-sm-4"></div>
                   <div class="col-sm-4">
                     <h6 class="text-center"><b></b></h6>
                     <h5 class="text-center"><b>PATIENT RECORD</b></h5>
                   </div>
                   <div class="col-sm-4"></div>
                 </div>
              <?php $attributes = array('id' => 'opd_form', 'class' => 'form-horizontal user'); ?>
               <?php echo form_open('admissioncontrol/opd_process', $attributes); ?>
              
                   <div class="row"> <!-- ROW 2 -->
                
                 <div class="col-sm-12 "><!-- first column -->
                  <div class="row">
                
                  
                  
                  </div><!--  ROW -->   
               
                   <div class="row">
                     <div style="margin-left: 4px;" class="col-sm-4">
                          <?php echo form_label('Lastname'); ?>
                   <input type="text"  class="form-control  <?= (form_error('lname') == "" ? '':'is-invalid') ?>"  name="lname" placeholder="Lastname" value="<?php echo set_value('lname') ?>">
                  <div class="text-danger text-center"><?php echo form_error('lname'); ?></div>
                     </div>
                     <div class="col-sm-4">
                       <?php echo form_label('Firstname'); ?>
                           <input type="text" style="margin-left: 4px;"  class="form-control  <?= (form_error('fname') == "" ? '':'is-invalid') ?>"  name="fname" placeholder="Firstname" value="<?php echo set_value('fname') ?>">
                  <div class="text-danger text-center"><?php echo form_error('fname'); ?></div>
                     </div>
                     <div class="col-sm-3">
                      <?php echo form_label('MiddleInitial'); ?>
                       <input type="text" style="margin-left: 2px;" class="form-control  <?= (form_error('middlen') == "" ? '':'is-invalid') ?>"  name="middlen" placeholder="Middleinitial" value="<?php echo set_value('middlen') ?>">
                  <div class="text-danger text-center"><?php echo form_error('middlen'); ?></div>
                     </div>

                   </div>
                  <div class="row">
                    <div style="margin-left: 4px;" class="col-sm-11">
                      <?php echo form_label('Address'); ?>
                                <input type="text" style="margin-left: 7px;" class="form-control  <?= (form_error('address') == "" ? '':'is-invalid') ?>"  name="address" placeholder="Permanent Address" value="<?php echo set_value('address') ?>">
                  <div class="text-danger text-center"><?php echo form_error('address'); ?></div>
                    </div>          
                  </div>
                   <div class="row">
                     <div style="margin-left: 4px;" class="col-sm-2">
                       <?php echo form_label('Age'); ?>
                                <input type="text" class="form-control  <?= (form_error('age') == "" ? '':'is-invalid') ?>"  name="age" placeholder="Age" value="<?php echo set_value('age') ?>">
                  <div class="text-danger text-center"><?php echo form_error('age'); ?></div>  
                     </div>
                    <div style="margin-left: 4px;" class="col-xs-3">
                       <?php echo form_label('Birthday'); ?>
                                <input type="date" class="form-control  <?= (form_error('datebirth') == "" ? '':'is-invalid') ?>"  name="datebirth" value="<?php echo set_value('datebirth') ?>">
                     <div class="text-danger text-center"><?php echo form_error('datebirth'); ?></div> 
                     </div>
                     <div style="margin-left: 4px;" class="col-sm-3">
                     
                     <div style="margin-left: 30px;" class="col-xs-3">
                        <?php echo form_label('Gender'); ?>
                      <select name="gen" class="form-control " name="gen">
                        <option value="">Select Gender</option>
                        <?php if(count($get_gender)): ?>
                        <?php foreach($get_gender as $gender): ?>
                        <option value=<?php echo $gender->g_name; ?><?php echo set_select('gen', $gender->g_name); ?>><?php echo $gender->g_name; ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                      </select>  
                   <div class="text-danger text-center"><?php echo form_error('gen'); ?></div>
                    </div>
                  </div>
                <div class="row">
                 

                     <div style="margin-left: 30px;" class="col-xs-3">
                        <?php echo form_label('Mobile/Tel No.'); ?>
                                <input type="number" class="form-control  <?= (form_error('number') == "" ? '':'is-invalid') ?>" placeholder="Mobile/Tel No."  name="number" value="<?php echo set_value('number') ?>">
                     <div class="text-danger text-center"><?php echo form_error('number'); ?></div>
                    </div>

                    <div style="margin-left: 20px;" class="col-xs-3">
                        <?php echo form_label('Email'); ?>
                                <input type="text" class="form-control  <?= (form_error('email') == "" ? '':'is-invalid') ?>" placeholder="Email"  name="email" value="<?php echo set_value('email') ?>">
                     <div class="text-danger text-center"><?php echo form_error('email'); ?></div> 
                   </div>

                    <div style="margin-left: 20px;" class="col-xs-3">
                      <?php echo form_label('Known Illness'); ?>
                                <input type="text" class="form-control <?= (form_error('illness') == "" ? '':'is-invalid') ?>" placeholder="Illness"  name="illness" value="<?php echo set_value('illness') ?>">
                     <div class="text-danger text-center"><?php echo form_error('illness'); ?></div>
                     </div>                   
                   </div>

                  </div>
                  <br><br>
            
                   <button style="margin-left:430px;" id="submitbtn" type="submit" class="btn btn-success btn-icon-split" name="submit">
                 <span class="icon text-white-100">
                  <i class="fas fa-arrow-right"></i>  
                    Add Record
                  </span>
                </button>
                      
               <?php echo form_close(); ?>
                
             </div>

 <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
