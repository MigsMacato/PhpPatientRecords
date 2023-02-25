<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php 

class Standardusercontrol extends CI_Controller {
 

 public function standarduserview(){

 $data['form'] = 'standardaccount/standardaccount_form';
 $data['title'] = "Login Account";

$this->load->view('standardaccount/standarduser_view', $data);

}


 
   public function login() {
    

    $this->form_validation->set_rules('user1', 'Username', 'trim|required');
    $this->form_validation->set_rules('pass1', 'Password', 'trim|required');

   if($this->form_validation->run() == FALSE){
  
   $data['form'] = 'standardaccount/standardaccount_form';
    $data['title'] = "Login Account";
    
    $this->load->view('standardaccount/standarduser_view', $data);


   } else {
   	   $user = $this->input->post('user1');
   	   $pass = $this->input->post('pass1');
      
        $login_id = $this->Standarduser_model->login_user($user, $pass);

       if($login_id){

       	  $data = array(
            'u_id' => $login_id,
            'u_user' => $user,
            'logged_in' => TRUE
       	  );

         $data['title'] = "Patient Record Management";
         $this->session->set_userdata($data);
         $this->session->set_flashdata('login_success', '<div class="text-center">Your are now logged in as Dr. <b>'.$this->session->userdata('u_user'). '</b></div>');
          redirect('standardusercontrol/docadmitdatatable', $data);  


       } else {

          $data['title'] = "Login Account";
         $this->session->set_flashdata('login_failed', 'Username or Password is incorrect!');
         redirect('standardusercontrol/login/#standard', $data);


       }
       
      
     }


   }



public function logout(){
 
 $this->session->sess_destroy();

redirect('standardusercontrol/standarduserview');

}



//dito yung table
public function docadmitdatatable() { // user id who insert data on datatable
  
   if($this->session->userdata('logged_in')) {
    
     $u_id = $this->session->userdata('su_id');
      
      
      $data['get_data'] = $this->Record_model->get_patient_records($u_id);
  
      $data['title'] = 'Records Table'; 
      $data['topbar'] = 'navbar-default';
      $data['main_view'] = 'admission/docadmitdatatable';
   
      $this->load->view('standardaccount/doctorpage', $data);
  
  
   } 
    
  }

public function outpatientview(){

$user_id = $this->session->userdata('su_id');

$data['get_doctor_patient'] = $this->Standarduser_model->get_patient($user_id);
$data['title'] = 'Outpatient Datatable';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['main_view'] = "standardaccount/datatable";

$this->load->view('standardaccount/doctorpage', $data);
}









public function findings_view($finding_id){
 
$data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Findings Overview';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['main_view'] = "standardaccount/findingsview";

$this->load->view('standardaccount/doctorpage', $data);



}


public function update_findings($finding_id){

$data['get_user'] = $this->Standarduser_model->get_users();
$data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Edit Findings';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/findingsviewform';
$data['main_view'] = "standardaccount/editfindingsview";

$this->load->view('standardaccount/doctorpage', $data);

}



public function update_admission($admission_id){

$data['get_user'] = $this->Standarduser_model->get_users();
$data['get_ward'] = $this->Standarduser_model->get_ward();
$data['get_patient_data'] = $this->Standarduser_model->get_patient_admission($admission_id);
$data['title'] = 'Edit Admission';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/admissionviewform';
$data['main_view'] = "standardaccount/editadmissionview";

$this->load->view('standardaccount/doctorpage', $data);

}


public function update_admission_process($admission_id){

$this->form_validation->set_rules('u_completediagnosis', 'Diagnosis', 'trim|required');
$this->form_validation->set_rules('u_medication', 'Medication', 'trim|required');

if($this->form_validation->run() == FALSE){

$data['get_user'] = $this->Standarduser_model->get_users();
$data['get_patient_data'] = $this->Standarduser_model->get_patient_admission($admission_id);
$data['title'] = 'Edit Admission';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/admissionviewform';
$data['main_view'] = "standardaccount/editadmissionview";

$this->load->view('standardaccount/doctorpage', $data);

} else {
   
     $userid = $this->session->userdata('su_id');

     $fname = $this->Standarduser_model->get_fname($admission_id);
      $mname = $this->Standarduser_model->get_mname($admission_id);
       $lname = $this->Standarduser_model->get_lname($admission_id);

  $data = array(

    'a_wardname' => $this->input->post('u_wards'),
  'a_physician_id' =>  $userid,
  'a_admittedby' => $this->input->post('u_admitted'),
  'a_dischargedate' =>  $this->input->post('u_discharge'),
  'a_completediagnosis' =>  $this->input->post('u_completediagnosis'),
  'a_medication' =>  $this->input->post('u_medication'),
  'a_conditiontodischarge' =>  $this->input->post('u_conditiontodischarge'),
  'a_remarks' =>  $this->input->post('u_remarks'),
  'a_complaint' => $this->input->post('u_complaint')

  );
  

  if($this->Standarduser_model->update_admission($data, $admission_id, $userid, $fname, $mname, $lname)){
  
  $id = $this->Standarduser_model->get_id_admission($admission_id);
 $data['get_patient_data'] = $this->Standarduser_model->get_patient_admission($admission_id);
$data['title'] = 'Edit Admission';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/admissionviewform';
$data['main_view'] = "standardaccount/editadmissionview";

 
  $this->session->set_flashdata('admission_success', 'Admission Updated Successfully');
 redirect('standardusercontrol/admission_view/'.$id->a_id.'/#admission', $data);

 

 }


  }

}




public function update_vital($finding_id){


$data['get_user'] = $this->Standarduser_model->get_users();
$data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Edit Vital Signs';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/editfindingsvitalsignform';
$data['main_view'] = "standardaccount/editfindingsvitalsign_view";

$this->load->view('standardaccount/doctorpage', $data);

}




public function update_vital_process($finding_id){

$this->form_validation->set_rules('u_bp', 'Blood Pressure', 'trim');
$this->form_validation->set_rules('u_rr', 'Respiratory Rate', 'trim');
$this->form_validation->set_rules('u_cr', 'Capillary Refill', 'trim');
$this->form_validation->set_rules('u_temp', 'Temperature', 'trim');
$this->form_validation->set_rules('u_pr', 'Diagnosis', 'trim');
$this->form_validation->set_rules('u_wt', 'Weight', 'trim');

if($this->form_validation->run() == FALSE){

 $data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Edit Vital Signs';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/editfindingsvitalsignform';
$data['main_view'] = "standardaccount/editfindingsvitalsign_view";
  
  $this->load->view('standardaccount/doctorpage', $data);


} else {
   

   $userid = $this->session->userdata('su_id');


     $fname = $this->Standarduser_model->get_fname_findings($finding_id);
      $mname = $this->Standarduser_model->get_mname_findings($finding_id);
       $lname = $this->Standarduser_model->get_lname_findings($finding_id);


  $data = array(

  'a_physician_id' =>  $userid,
  'a_bp' => $this->input->post('u_bp'),
  'a_rr' =>  $this->input->post('u_rr'),
  'a_cr' =>  $this->input->post('u_cr'),
  'a_temp' =>  $this->input->post('u_temp'),
  'a_pr' =>  $this->input->post('u_pr'),
  'a_wt' =>  $this->input->post('u_wt'),
  'a_complaint' => $this->input->post('u_complaint')

  );
  
   if($this->Standarduser_model->update_vital_sign($data, $finding_id, $userid, $fname,  $mname, $lname)){
   
$id = $this->Standarduser_model->get_id($finding_id);

$data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Edit Vital Signs';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/editfindingsvitalsignform';
$data['main_view'] = "standardaccount/editfindingsvitalsign_view";

$this->session->set_flashdata('vitalsign_success', 'Vital Signs Updated');
redirect('standardusercontrol/findings_view/'.$id->a_id.'/#findings', $data);


   }

}



}






public function update_process($finding_id){


$this->form_validation->set_rules('u_history', 'Present illness', 'required|trim');
$this->form_validation->set_rules('u_physical', 'Physical Exam');
$this->form_validation->set_rules('u_diagnosis', 'Diagnosis', 'required');
$this->form_validation->set_rules('u_medication', 'Medication', 'required');

if($this->form_validation->run() == FALSE){

$data['get_user'] = $this->Standarduser_model->get_users();
$data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Edit Findings';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['form'] = 'standardaccount/findingsviewform';
$data['main_view'] = "standardaccount/editfindingsview";

$this->load->view('standardaccount/doctorpage', $data);


} else {
  
  $userid = $this->session->userdata('su_id');


     $fname = $this->Standarduser_model->get_fname_f($finding_id);
      $mname = $this->Standarduser_model->get_mname_f($finding_id);
       $lname = $this->Standarduser_model->get_lname_f($finding_id);


  $data = array(
    'a_physician_id' => $userid,
   'a_historypresentillness' => $this->input->post('u_history'),
   'a_physicalexam' => $this->input->post('u_physical'),
   'a_diagnosis' => $this->input->post('u_diagnosis'),
   'a_medication' => $this->input->post('u_medication')
  );
 
  if($this->Standarduser_model->updatefindings($data, $finding_id,  $userid, $fname, $mname, $lname)){

$id = $this->Standarduser_model->get_id($finding_id);
$data['get_patient_data'] = $this->Standarduser_model->get_patient_data($finding_id);
$data['title'] = 'Edit Findings';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['main_view'] = "standardaccount/findingsview";
 
 $this->session->set_flashdata('findings_success', 'Findings Updated Successfully');
 redirect('standardusercontrol/findings_view/'.$id->a_id.'/#findings', $data);
 
  }

}


}



public function admission_view($admission_id){


$data['get_patient_data'] = $this->Standarduser_model->get_patient_admission($admission_id);
$data['title'] = 'Admission Overview';
$data['topbar'] = 'standardaccount/doctorpagenavbar';
$data['main_view'] = "standardaccount/admissionview";

$this->load->view('standardaccount/doctorpage', $data);




}









//==================================================================================

public function add_to_doctor($findings_id) { 


$this->form_validation->set_rules('e_patientfname','Firstname','required|trim|alpha');
$this->form_validation->set_rules('e_patientmname','Middlename', 'required|trim');
$this->form_validation->set_rules('e_patientlname','Lastname', 'required|trim|alpha');
$this->form_validation->set_rules('e_gender','Gender', 'required|trim');
$this->form_validation->set_rules('e_age','Age', 'required|trim');
$this->form_validation->set_rules('e_chief_complaint','Chief Complaint','alpha');
$this->form_validation->set_rules('e_historyillness','History of Illness', 'required|trim');
$this->form_validation->set_rules('e_bp','Blood Pressure');
$this->form_validation->set_rules('e_rr','Respiratory Rate');
$this->form_validation->set_rules('e_cr','Capillary Refill');
$this->form_validation->set_rules('e_temp','Temperature');
$this->form_validation->set_rules('e_wt','Weight');
$this->form_validation->set_rules('e_pr','Pulse Rate');
$this->form_validation->set_rules('e_diagnosis','Diagnosis');
$this->form_validation->set_rules('e_medication','Medication / Treatment');
$this->form_validation->set_rules('e_physician','Username','required', array('required' => 'Please select the username of the doctor!'));

if($this->form_validation->run() == FALSE){

$doctor = 'Doctor';

$data['get_user'] = $this->Record_model->get_users_account($doctor);
$data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
$data['title'] = 'Add to Doctor';
$data['topbar'] = 'navbar-default';
$data['form'] = 'admission/addfindingsdataform';
$data['main_view'] = "admission/addfindingsdata";

$this->load->view('layouts/central_template', $data);


 } else {
 
  
   if($this->input->post('e_historyillness') == "*Under Observation"){


  $date = date("Y-m-d");

     $user_id = $this->session->userdata('u_id');
     
      $data = array(
   'a_user_id' => $user_id,
   'a_fname' => $this->input->post('e_patientfname'),
   'a_mname' => $this->input->post('e_patientmname'),
   'a_lname' => $this->input->post('e_patientlname'),
   'a_gender' => $this->input->post('e_gender'),
   'a_age' => $this->input->post('e_age'),
   'a_complaint' => $this->input->post('e_chief_complaint'),
   'a_historypresentillness' => $this->input->post('e_historyillness'),
   'a_bp' => $this->input->post('e_bp'),
   'a_rr' => $this->input->post('e_rr'),
   'a_cr' => $this->input->post('e_cr'),
   'a_temp' => $this->input->post('e_temp'),
   'a_wt' => $this->input->post('e_wt'),
   'a_diagnosis' => $this->input->post('e_diagnosis'),
   'a_medication' => $this->input->post('e_medication'),
   'a_pr' => $this->input->post('e_pr'),
   'a_physician_id' => $this->input->post('e_physician'),
   'a_date' => $date 
 
   );

   if($this->Standarduser_model->add_findings_to_doctor($data)){

$doctor = 'Doctor';

$data['get_user'] = $this->Record_model->get_users_account($doctor);
     $data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
      $data['title'] = "Add to Doctor";
      $data['topbar'] = 'navbar-default';
      $data['form'] = 'admission/addfindingsdataform';
      $data['main_view'] = 'admission/addfindingsdata';
  
      $this->session->set_flashdata('add_to_doctor_success', "Patient Findings is Added to Doctor's Account");
      redirect('admissioncontrol/findingsview/'.$findings_id.'#findings', $data);



    } 
 





   } else {
     


  $date = date("Y-m-d");

     $user_id = $this->session->userdata('u_id');
     
      $data = array(
   'of_user_id' => $user_id,
   'of_fname' => $this->input->post('e_patientfname'),
   'of_mname' => $this->input->post('e_patientmname'),
   'of_lname' => $this->input->post('e_patientlname'),
   'of_gender' => $this->input->post('e_gender'),
   'of_age' => $this->input->post('e_age'),
   'of_complaint' => $this->input->post('e_chief_complaint'),
   'of_historypresentillness' => $this->input->post('e_historyillness'),
   'of_bp' => $this->input->post('e_bp'),
   'of_rr' => $this->input->post('e_rr'),
   'of_cr' => $this->input->post('e_cr'),
   'of_temp' => $this->input->post('e_temp'),
   'of_wt' => $this->input->post('e_wt'),
   'of_diagnosis' => $this->input->post('e_diagnosis'),
   'of_medication' => $this->input->post('e_medication'),
   'of_pr' => $this->input->post('e_pr'),
   'of_physician_id' => $this->input->post('e_physician'),
   'of_date' => $date 
 
   );
 
      if($this->Standarduser_model->add_oldfindings($data)){
 
          
$doctor = 'Doctor';

$data['get_user'] = $this->Record_model->get_users_account($doctor);
     $data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
      $data['title'] = "Add to Doctor";
      $data['topbar'] = 'navbar-default';
      $data['form'] = 'admission/addfindingsdataform';
      $data['main_view'] = 'admission/addfindingsdata';
  
      $this->session->set_flashdata('add_to_doctor_success', "Patient Findings is Added to Doctor's Account");
      redirect('admissioncontrol/findingsview/'.$findings_id.'#findings', $data);


      }

      



   }


 }


} 




public function add_admission_doctor($admission_id) {


$this->form_validation->set_rules('e_patientfname','Firstname','required|trim|alpha');
$this->form_validation->set_rules('e_patientmname','Middlename', 'required|trim');
$this->form_validation->set_rules('e_patientlname','Lastname', 'required|trim|alpha');
$this->form_validation->set_rules('e_gender','Gender', 'required|trim');
$this->form_validation->set_rules('e_age','Age', 'required|trim');
$this->form_validation->set_rules('e_chief_complaint','Chief Complaint','alpha');
$this->form_validation->set_rules('e_ward','Ward', 'required|trim');
$this->form_validation->set_rules('e_physician','Username','required', array('required' => 'Please select the username of the doctor!'));

if($this->form_validation->run() == FALSE){

$doctor = 'Doctor';

$data['get_user'] = $this->Record_model->get_users_account($doctor);
$data['get_admission_view'] = $this->Record_model->get_data_admission($admission_id);
$data['title'] = 'Add to Doctor';
$data['topbar'] = 'navbar-default';
$data['form'] = 'admission/addadmissiondataform';
$data['main_view'] = "admission/addadmissiondata";

$this->load->view('layouts/central_template', $data);


 } else {
 

if($this->input->post('e_ward') == "*Under.Observation"){
  

    $date = date("Y-m-d");

     $user_id = $this->session->userdata('u_id');
     
      $data = array(
   'a_user_id' => $user_id,
   'a_fname' => $this->input->post('e_patientfname'),
   'a_mname' => $this->input->post('e_patientmname'),
   'a_lname' => $this->input->post('e_patientlname'),
   'a_gender' => $this->input->post('e_gender'),
   'a_age' => $this->input->post('e_age'),
   'a_admittedby' => $this->input->post('e_admitted'),
   'a_complaint' => $this->input->post('e_chief_complaint'),
   'a_wardname' => $this->input->post('e_ward'),
   'a_completediagnosis' => $this->input->post('e_diagnosis'),
   'a_medication' => $this->input->post('e_medication'),
   'a_dischargedate' => $this->input->post('e_discharge'),
   'a_conditiontodischarge' => $this->input->post('e_condition'),
   'a_physician_id' => $this->input->post('e_physician'),
   'a_date' => $date 
 
   );

   if($this->Standarduser_model->add_admission_to_doctor($data)){

$doctor = 'Doctor';

$data['get_user'] = $this->Record_model->get_users_account($doctor);
$data['get_admission_view'] = $this->Record_model->get_data_admission($admission_id);
$data['title'] = 'Add to Doctor';
$data['topbar'] = 'navbar-default';
$data['form'] = 'admission/addadmissiondataform';
$data['main_view'] = "admission/addadmissiondata";
  
      $this->session->set_flashdata('add_to_doctor_success', "Patient Admission is Added to Doctor's Account");
      redirect('admissioncontrol/admissionview/'.$admission_id.'#admission', $data);



   }



} else {

  
     $date = date("Y-m-d");

     $user_id = $this->session->userdata('u_id');
     
      $data = array(
   'oad_user_id' => $user_id,
   'oad_fname' => $this->input->post('e_patientfname'),
   'oad_mname' => $this->input->post('e_patientmname'),
   'oad_lname' => $this->input->post('e_patientlname'),
   'oad_gender' => $this->input->post('e_gender'),
   'oad_age' => $this->input->post('e_age'),
   'oad_admittedby' => $this->input->post('e_admitted'),
   'oad_complaint' => $this->input->post('e_chief_complaint'),
   'oad_wardname' => $this->input->post('e_ward'),
   'oad_completediagnosis' => $this->input->post('e_diagnosis'),
   'oad_medication' => $this->input->post('e_medication'),
   'oad_dischargedate' => $this->input->post('e_discharge'),
   'oad_conditiontodischarge' => $this->input->post('e_condition'),
   'oad_physician_id' => $this->input->post('e_physician'),
   'oad_date' => $date 
 
   );

   if($this->Standarduser_model->add_old_admission($data)){

$doctor = 'Doctor';

$data['get_user'] = $this->Record_model->get_users_account($doctor);
$data['get_admission_view'] = $this->Record_model->get_data_admission($admission_id);
$data['title'] = 'Add to Doctor';
$data['topbar'] = 'navbar-default';
$data['form'] = 'admission/addadmissiondataform';
$data['main_view'] = "admission/addadmissiondata";
  
      $this->session->set_flashdata('add_to_doctor_success', "Patient Admission is Added to Doctor's Account");
      redirect('admissioncontrol/admissionview/'.$admission_id.'#admission', $data);



   }





}


 

 }


}





public function oldrecords(){

 $userid = $this->session->userdata('su_id');


$data['oldrecords'] = $this->Standarduser_model->get_old_findings($userid);

$data['title'] = 'Old Findings';
$data['topbar'] = 'standardaccount/oldrecordsnavbar';
$data['main_view'] = "standardaccount/oldrecordsview";

$this->load->view('standardaccount/doctorpage', $data);


}




public function oldfindingsview($oldfindings_id){

$data['get_patient_data'] = $this->Standarduser_model->get_old_findings_data($oldfindings_id);
$data['title'] = 'Old Findings';
$data['topbar'] = 'standardaccount/oldrecordsnavbar';
$data['main_view'] = "standardaccount/oldrecordfindingsview";

$this->load->view('standardaccount/doctorpage', $data);


}












public function oldadmissionview(){

   $userid = $this->session->userdata('su_id');

$data['get_doctor_patient'] = $this->Standarduser_model->get_old_admission($userid);

$data['title'] = 'Old Admission Records';
$data['topbar'] = 'standardaccount/oldrecordsnavbar';
$data['main_view'] = "standardaccount/oldadmissiondata";

$this->load->view('standardaccount/doctorpage', $data);


}



public function oldadmission_data($oldadmissiondata){

$data['get_patient_data'] = $this->Standarduser_model->get_old_admission_data($oldadmissiondata);
$data['title'] = 'Old Admission Records';
$data['topbar'] = 'standardaccount/oldrecordsnavbar';
$data['main_view'] = "standardaccount/oldadmission_view";

$this->load->view('standardaccount/doctorpage', $data);



}
//start of addmision
public function opd_form(){



   $data['title'] = 'Add Patient Form';
   
   $data['get_civilstat'] = $this->Record_model->get_civilstat();
   $data['get_gender'] = $this->Record_model->get_gender();
   
   $data['topbar'] = 'navbar-default';
   $data['main_view'] = 'admission/opdform';
   
   $this->load->view('layouts/central_template', $data);
   
   
   }
   
   public function opd_process(){
   
   
   
   $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|alpha_numeric_spaces');
   $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|alpha_numeric_spaces');
   $this->form_validation->set_rules('middlen', 'Middlename', 'trim|alpha');
   $this->form_validation->set_rules('address', 'Address', 'required');
   $this->form_validation->set_rules('illness', 'Illness', 'required');
   $this->form_validation->set_rules('age', 'Age', 'trim|required|numeric|min_length[2]|max_length[2]');
   $this->form_validation->set_rules('gen', 'Gender', 'trim|required',array('required'=>'Please select gender'));
   //$this->form_validation->set_rules('birthplace', 'Birthplace', 'required');
   $this->form_validation->set_rules('datebirth', 'Date of Birth', 'trim|required');
   //$this->form_validation->set_rules('civilstat', 'Civil Status', 'trim|required');
   $this->form_validation->set_rules('email', 'email', 'trim|required');
   $this->form_validation->set_rules('number', 'Mobile/Tel No.', 'trim|required');
   
   
   if($this->form_validation->run() == FALSE) {
   
   
   $data['title'] = 'Add Patient Form';
   $data['topbar'] = 'navbar-default';
   $data['main_view'] = 'admission/opdform'; 
   //$data['get_civilstat'] = $this->Record_model->get_civilstat();
   $data['get_gender'] = $this->Record_model->get_gender();
   $this->load->view('layouts/central_template', $data);
   
   
   } else {
   
   $date = date("Y-m-d"); 
   $month = date("M", strtotime("+8 HOURS"));
   $year = date("Y", strtotime("+8 HOURS"));
   
   
    $data = array(
      //'pr_user_id' => $this->session->userdata('u_id'),
      'pr_date' =>  $date,
      'pr_lname' => $this->input->post('lname'),
      'pr_fname' => $this->input->post('fname'),
      'pr_mname' => $this->input->post('middlen'),
      'pr_addrs' => $this->input->post('address'),
      'pr_age' => $this->input->post('age'),
      'pr_gen' => $this->input->post('gen'),
      'pr_bdate' => $this->input->post('datebirth'),
      'pr_email' => $this->input->post('email'),
      //'pr_bplace' => $this->input->post('birthplace'),
      //'pr_civilstat' => $this->input->post('civilstat'),
      'pr_number' => $this->input->post('number'),
      //'pr_religion' => $this->input->post('religion'),
      'pr_illness' => $this->input->post('illness'),
      'month' => $month,
      'year' => $year
   
    );
   
       if($this->Record_model->patient_record($data)){
     
         $data['title'] = 'Add Patient Data';
       $data['topbar'] = 'navbar-default';
         $data['main_view'] = 'admission/opdform';
           
       $this->session->set_flashdata('patientrecord_success', 'Patient Data Added');
         $this->load->view('layouts/central_template', $data);
   
       } 
   
      } 
      
    }
   
   
   
   public function edit_form($pr_id){
   
   
   
   $data['title'] = 'Edit Patient Form';
   
   //$data['get_civilstat'] = $this->Record_model->get_civilstat();
   $data['get_gender'] = $this->Record_model->get_gender();
   $data['pr_id'] = $this->Record_model->get_patient_data($pr_id);
   $data['topbar'] = 'navbar-default';
   $data['main_view'] = 'admission/edit_opd_view';
   
   $this->load->view('layouts/central_template', $data);
   
   
   }
   public function dashboardcontrol(){
   
   
   
     $data['title'] = 'Dashboard';
     $data['topbar'] = 'navbar-default';
     $data['main_view'] = 'dashboard/homedashboadrd_view';
     
     $this->load->view('layouts/central_template', $data);
     
     
     }
   
   
   
   public function edit_opd_form($pr_id) {
   
     $this->form_validation->set_rules('lname', 'Lastname', 'trim|required|alpha_numeric_spaces');
     $this->form_validation->set_rules('fname', 'Firstname', 'trim|required|alpha_numeric_spaces');
     $this->form_validation->set_rules('middlen', 'Middlename', 'trim|alpha');
     $this->form_validation->set_rules('address', 'Address', 'required');
     $this->form_validation->set_rules('illness', 'Illness', 'required');
     $this->form_validation->set_rules('age', 'Age', 'trim|required|numeric|min_length[2]|max_length[2]');
     $this->form_validation->set_rules('gen', 'Gender', 'trim|required',array('required'=>'Please select gender'));
     //$this->form_validation->set_rules('birthplace', 'Birthplace', 'required');
     $this->form_validation->set_rules('datebirth', 'Date of Birth', 'trim|required');
     //$this->form_validation->set_rules('civilstat', 'Civil Status', 'trim|required');
     $this->form_validation->set_rules('email', 'email', 'trim|required');
     $this->form_validation->set_rules('number', 'Mobile/Tel No.', 'trim|required');
   
   
   if($this->form_validation->run() == FALSE) {
   
   
   $data['title'] = 'Edit Patient Form';
   $data['topbar'] = 'navbar-default';
   $data['main_view'] = 'admission/edit_opd_view'; 
   $data['pr_id'] = $this->Record_model->get_patient_data($pr_id);
   //$data['get_civilstat'] = $this->Record_model->get_civilstat();
   $data['get_gender'] = $this->Record_model->get_gender();
   $this->load->view('layouts/central_template', $data);
   
   
   } else {
   
    $data = array(
      'pr_user_id' => $this->session->userdata('u_id'),
      'pr_date' => $this->input->post('date'),
      'pr_lname' => $this->input->post('lname'),
      'pr_fname' => $this->input->post('fname'),
      'pr_mname' => $this->input->post('middlen'),
      'pr_addrs' => $this->input->post('address'),
      'pr_age' => $this->input->post('age'),
      'pr_gen' => $this->input->post('gen'),
      'pr_bdate' => $this->input->post('datebirth'),
      //'pr_bplace' => $this->input->post('birthplace'),
      //'pr_civilstat' => $this->input->post('civilstat'),
      'pr_number' => $this->input->post('number'),
      'pr_email' => $this->input->post('email'),
      'pr_illness' => $this->input->post('illness'),
      //'pr_occup' => $this->input->post('occup')
   
    );
   
    if($this->Record_model->update_patient_info($pr_id, $data)){
   
       $data['title'] = 'Edit Patient Data';
       $data['topbar'] = 'navbar-default';
       $data['main_view'] = 'admission/edit_opd_view';
       $data['pr_id'] = $this->Record_model->get_patient_data($pr_id);
       $this->session->set_flashdata('patientrecord_updated', 'Patient Data Updated');
       redirect('admissioncontrol/admitdatatable', $data);
   
     }
   
    } 
   
   }
   
   
   public function patient_edit_option($pr_id) {
   
   
      $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
      $data['title'] = "Edit Patient Details";
      $data['topbar'] = 'navbar-default';
      $data['get_civilstat'] = $this->Record_model->get_civilstat();
      $data['get_gender'] = $this->Record_model->get_gender();
      $data['edit_option_view'] = 'admission/patient_edit_option';
      $data['main_view'] = 'admission/patient_editoption_view';
      $data['get_findings_data'] = $this->Record_model->get_patient_findings_id($pr_id); //Send ID to method get_patient_findings_id 
      $data['get_admission_data'] = $this->Record_model->get_patient_admission_id($pr_id);
   
   
      $this->load->view('layouts/central_template', $data);
   
   
   
   }
   
   
   
   public function edit_option_process($pr_id) {
   
   
   $this->form_validation->set_rules('e_address', 'Address', 'required');
   $this->form_validation->set_rules('e_occup', 'Occupation', 'required');
   $this->form_validation->set_rules('e_age', 'Age', 'trim|required|numeric|min_length[2]|max_length[2]');
   $this->form_validation->set_rules('e_gen', 'Gender', 'trim|required',array('required'=>'Please select gender'));
   $this->form_validation->set_rules('e_bplace', 'Birthplace', 'required');
   $this->form_validation->set_rules('e_bdate', 'Date of Birth', 'trim|required');
   $this->form_validation->set_rules('e_civilstat', 'Civil Status', 'trim|required');
   $this->form_validation->set_rules('e_religion', 'Religion', 'trim|required|alpha');
   $this->form_validation->set_rules('e_number', 'Mobile/Tel No.', 'trim|required');
   $this->form_validation->set_rules('e_date', 'Date Added', 'trim|required');
   
   $this->benchmark->mark('edit_option_processstart');
   if($this->form_validation->run() == FALSE) {
      $data['get_civilstat'] = $this->Record_model->get_civilstat();
      $data['get_gender'] = $this->Record_model->get_gender();
      $data['title'] = "Edit Patient Details";
      $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
      $data['get_findings_data'] = $this->Record_model->get_patient_findings_id($pr_id); //Send ID to method get_patient_findings_id 
      $data['topbar'] = 'navbar-default';
      $data['edit_option_view'] = 'admission/patient_edit_option';
      $data['main_view'] = 'admission/patient_editoption_view';
      $data['get_admission_data'] = $this->Record_model->get_patient_admission_id($pr_id);
   
      $this->load->view('layouts/central_template', $data);
   
   } else {
   
    $data = array(
   
      'pr_user_id' => $this->session->userdata('u_id'),
      'pr_addrs' => $this->input->post('e_address'),
      'pr_age' => $this->input->post('e_age'),
      'pr_gen' => $this->input->post('e_gen'),
      'pr_bdate' => $this->input->post('e_bdate'),
      'pr_bplace' => $this->input->post('e_bplace'),
      'pr_civilstat' => $this->input->post('e_civilstat'),
      'pr_number' => $this->input->post('e_number'),
      'pr_religion' => $this->input->post('e_religion'),
      //'pr_occup' => $this->input->post('e_occup'),
      'pr_date' => $this->input->post('e_date')
   
    );
    $this->benchmark->mark('edit_option_process_end');
   
    if($this->Record_model->update_patient_info($pr_id, $data)){
   
   
      $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
      $data['title'] = "Patient Details";
      $data['get_civilstat'] = $this->Record_model->get_civilstat();
      $data['get_findings_data'] = $this->Record_model->get_patient_findings_id($pr_id); //Send ID to method get_patient_findings_id 
      $data['get_gender'] = $this->Record_model->get_gender();
      $data['get_admission_data'] = $this->Record_model->get_patient_admission_id($pr_id);
      $data['topbar'] = 'navbar-default';
      $data['main_view'] = 'admission/patientdataview';
   
       $this->session->set_flashdata('patientrecordoption_updated', 'Patient Details Updated');
      $this->load->view('layouts/central_template', $data);
   
     }
   
    } 
   
   }
   
       
    public function admitdatatable() { // user id who insert data on datatable
     
    if($this->session->userdata('logged_in')) {
     
      $u_id = $this->session->userdata('u_id');
       
       
       $data['get_data'] = $this->Record_model->get_patient_records($u_id);
   
       $data['title'] = 'Records Table'; 
       $data['topbar'] = 'navbar-default';
       $data['main_view'] = 'admission/admitdatatable';
    
       $this->load->view('layouts/central_template', $data);
   
   
    } 
     
   }

   
   
   public function patientdataview($pr_id){
   
   $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
   
   $data['get_findings_data'] = $this->Record_model->get_patient_findings_id($pr_id); //Send ID to method get_patient_findings_id 
   
   $data['get_admission_data'] = $this->Record_model->get_patient_admission_id($pr_id);
   
   $data['title'] = "Patient Records";
   $data['topbar'] = 'navbar-default';
   $data['main_view'] = 'admission/patientdataview';
   $this->load->view('layouts/central_template', $data);
   
   
   }
   
   
   
   
   
   
   
   
   
   
   public function add_findings($pr_id){
   
    
   $this->form_validation->set_rules('a_history', 'History of Present Illness', 'required');
   $this->form_validation->set_rules('a_bp', 'Blood Pressure');
   $this->form_validation->set_rules('a_rr', 'Respiratory Rate');
   $this->form_validation->set_rules('a_cr', 'Capillary Refill');
   $this->form_validation->set_rules('a_temp', 'Temperature');
   $this->form_validation->set_rules('a_wt', 'Weight');
   $this->form_validation->set_rules('a_pr', 'Pulse Rate');
   $this->form_validation->set_rules('a_physician', 'Attending Physician');
   
   
   
   
   if($this->form_validation->run() == FALSE){
    
    $patient_id = $this->Record_model->get_patient_data($pr_id);
    $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
    $data['fieldsphysician'] = $this->Record_model->fetch_medical_field();
    $data['add_physician'] = $this->Record_model->get_physician();
    $data['title'] = "Add Out Patient Findings";
    $data['topbar'] = 'navbar-default';
        $data['get_admission_data'] = $this->Record_model->get_patient_admission_id($pr_id);
   
    $data['add_findings'] = "admission/addfindingsform";
    $data['main_view'] = 'admission/addfindings_view';
   
    $this->load->view('layouts/central_template', $data);
   
   
   } else {
      
      
     $date = date("Y-m-d");
   
      $data = array(
      'pr_findings_id' => $pr_id,
      'f_historypresentillness' => $this->input->post('a_history'),
      'f_bp' => $this->input->post('a_bp'),
      'f_rr' => $this->input->post('a_rr'),
      'f_cr' => $this->input->post('a_cr'),
      'f_temp' => $this->input->post('a_temp'),
      'f_wt' => $this->input->post('a_wt'),
      'f_pr' => $this->input->post('a_pr'),
      'f_nameofphysician' => $this->input->post('a_physician'),
      'f_date' => $date
      );
   
      
       if($this->Record_model->add_findings_data($pr_id, $data)){
         
         $patient_id = $this->Record_model->get_patient_data($pr_id);
         $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
         $data['add_physician'] = $this->Record_model->get_physician();
         $data['title'] = "Add Out Patient Findings";
         $data['topbar'] = 'navbar-default';
             $data['get_admission_data'] = $this->Record_model->get_patient_admission_id($pr_id);
   
         $data['add_findings'] = "admission/addfindingsform";
         $data['main_view'] = 'admission/addfindings_view';
     
         $this->session->set_flashdata('add_finding_success', 'Findings Added');
         redirect('admissioncontrol/patientdataview/'.$pr_id.'#findings', $data);
   
   
       }
   
   }
   
   
   
   }
   
   
   
   
   
   
   public function edit_findings($findings_id){
   
   
   $this->form_validation->set_rules('e_chief_complaint', 'Chief Complaint');
   $this->form_validation->set_rules('e_historyillness', 'History of Present Illness', 'required');
   $this->form_validation->set_rules('e_bp', 'Blood Pressure');
   $this->form_validation->set_rules('e_rr', 'Respiratory Rate');
   $this->form_validation->set_rules('e_cr', 'Capillary Refill');
   $this->form_validation->set_rules('e_temp', 'Temperature');
   $this->form_validation->set_rules('e_wt', 'Weight');
   $this->form_validation->set_rules('e_pr', 'Pulse Rate');
   $this->form_validation->set_rules('e_physicalexam', 'Physical Examination');
   $this->form_validation->set_rules('e_diagnosis', 'Diagnosis', 'required');
   $this->form_validation->set_rules('e_medical_treatment', 'Medication/Treatment', 'required');
   $this->form_validation->set_rules('e_physician', 'Attending Physician', 'required');
   
   
   if($this->form_validation->run() == FALSE){
    
   
    $data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
   
    
    $data['title'] = "Edit Out Patient Findings";
    $data['topbar'] = 'navbar-default';
    $data['add_physician'] = $this->Record_model->get_physician();
    $data['edit_findings'] = "admission/editfindingsform";
    $data['main_view'] = 'admission/editfindings_view';
   
    $this->load->view('layouts/central_template', $data);
   
   
   } else {
   
   
       $finding_update_id = $this->Record_model->get_findings_id_update($findings_id);
   
      $data = array(
      'pr_findings_id' => $finding_update_id,
      'f_chiefcomplaint' => $this->input->post('e_chief_complaint'),
      'f_historypresentillness' => $this->input->post('e_historyillness'),
      'f_bp' => $this->input->post('e_bp'),
      'f_rr' => $this->input->post('e_rr'),
      'f_cr' => $this->input->post('e_cr'),
      'f_temp' => $this->input->post('e_temp'),
      'f_wt' => $this->input->post('e_wt'),
      'f_pr' => $this->input->post('e_pr'),
      'f_physicalexam' => $this->input->post('e_physicalexam'),
      'f_diagnosis' => $this->input->post('e_diagnosis'),
      'f_medication' => $this->input->post('e_medical_treatment'),
      'f_nameofphysician' => $this->input->post('e_physician'),
    
      );
   
      
       if($this->Record_model->edit_findings_data($findings_id, $data)){
         
       
         $data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
   
         $data['title'] = "Edit Out Patient Findings";
         $data['topbar'] = 'navbar-default';
         $data['add_physician'] = $this->Record_model->get_physician();
         $data['edit_findings'] = "admission/editfindingsform";
         $data['main_view'] = 'admission/editfindings_view';
     
         $this->session->set_flashdata('edit_finding_success', 'Findings Updated ');
         redirect('admissioncontrol/findingsview/'.$findings_id.'#findings', $data);
   
   
       }
   
   }
   
   
   
     
   }
   
   
   public function findingsview($findings_id){
   
   
       
         $data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
   
   
         $data['title'] = "Out Patient Findings";
         $data['topbar'] = 'navbar-default';
         $data['main_view'] = 'admission/findingsdataview';
     
        $this->load->view('layouts/central_template', $data);
     
   
   
   }
   
   
   
   
   
   
   
   
   public function admit_form($pr_id){
   
    
   $this->form_validation->set_rules('a_wards', 'Wards', 'trim|required',array('required'=>'Please select ward'));
   $this->form_validation->set_rules('a_physician', 'Attending Physician', 'trim|required',array('required'=>'Please select physician'));
   $this->form_validation->set_rules('a_father', 'For Minor: Name of Parents');
   $this->form_validation->set_rules('a_mother', 'For Minor: Name of Parents');
   $this->form_validation->set_rules('a_chargeaccount', 'Charge Account to');
   $this->form_validation->set_rules('a_relationtopatient', 'Relation to Patient');
   $this->form_validation->set_rules('a_address', 'Address');
   $this->form_validation->set_rules('a_number', 'Number');
   $this->form_validation->set_rules('a_totalpayment', 'Total Payment Made');
   $this->form_validation->set_rules('a_admitted', 'Admitted By', 'trim|required');
   
   
   
   if($this->form_validation->run() == FALSE){
    
    $patient_id = $this->Record_model->get_patient_data($pr_id);
     $data['get_findings_data'] = $this->Record_model->get_patient_findings_id($pr_id);
    $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
    $data['add_physician'] = $this->Record_model->get_physician();
    $data['get_ward'] = $this->Record_model->get_ward();
    $data['title'] = "Admit Patient";
    $data['topbar'] = 'navbar-default';
    $data['admitting_view'] = "admission/admitting_form";
    $data['main_view'] = 'admission/admitting_view';
   
    $this->load->view('layouts/central_template', $data);
   
   
   } else {
      
      
     $admissiondate = date("Y-m-d");
   
   
   
      $data = array(
      'pr_admission_id' => $pr_id,
      'ad_wardname' => $this->input->post('a_wards'),
      'ad_physician' => $this->input->post('a_physician'),
      'ad_date' => $admissiondate,
      'ad_father' => $this->input->post('a_father'),
      'ad_mother' => $this->input->post('a_mother'),
      'ad_chargetoaccount' => $this->input->post('a_chargeaccount'),
      'ad_relationtopatient' => $this->input->post('a_relationtopatient'),
      'ad_address' => $this->input->post('a_address'),
      'ad_number' => $this->input->post('a_number'),
      'ad_totalpayment' => $this->input->post('a_totalpayment')
      );
   
      
       if($this->Record_model->add_admission_data($pr_id, $data)){
         
         $patient_id = $this->Record_model->get_patient_data($pr_id);
          $data['get_findings_data'] = $this->Record_model->get_patient_findings_id($pr_id);
         $data['get_data'] = $this->Record_model->get_patient_data($pr_id);
         $data['add_physician'] = $this->Record_model->get_physician();
         $data['title'] = "Admit Patient";
         $data['topbar'] = 'navbar-default';
         $data['admitting_view'] = "admission/admitting_form";
         $data['main_view'] = 'admission/admitting_view';
     
         $this->session->set_flashdata('add_admit_success', 'Admission Added');
         redirect('admissioncontrol/patientdataview/'.$pr_id.'#admission', $data);
   
   
       }
   
   }
   
   
   
   
   }
   
   
   public function edit_admission($admission_id){
   
   $this->form_validation->set_rules('e_wardname', 'Wards', 'trim|required',array('required'=>'Please select ward'));
   $this->form_validation->set_rules('e_physician', 'Attending Physician', 'trim|required',array('required'=>'Please select physician'));
   $this->form_validation->set_rules('e_admittedby', 'Admitted by','trim|required');
   $this->form_validation->set_rules('e_discharge', 'Discharge Date');
   $this->form_validation->set_rules('e_father', 'For Minor: Name of Parents');
   $this->form_validation->set_rules('e_mother', 'For Minor: Name of Parents');
   $this->form_validation->set_rules('e_chargeofaccount', 'Charge Account to');
   $this->form_validation->set_rules('e_relationtopatient', 'Relation to Patient');
   $this->form_validation->set_rules('e_address', 'Address');
   $this->form_validation->set_rules('e_number', 'Number','min_length[11]|max_length[11]');
   $this->form_validation->set_rules('e_totalpayment', 'Total Payment Made');
   $this->form_validation->set_rules('e_complain', 'Chief Complaint');
   $this->form_validation->set_rules('e_completediagnosis', 'Complete Diagnosis');
   $this->form_validation->set_rules('e_medication', 'Medication/Treatment');
   $this->form_validation->set_rules('e_conditiondischarge', 'Condition on Discharge');
   $this->form_validation->set_rules('e_remarks', 'Remarks');
   
   
   if($this->form_validation->run() == FALSE){
    
   $data['get_data_admission'] = $this->Record_model->get_data_admission($admission_id);
   
    $data['get_physician'] = $this->Record_model->get_physician();
    $data['get_ward'] = $this->Record_model->get_ward();
    $data['title'] = "Edit Admission";
    $data['topbar'] = 'navbar-default';
    $data['edit_admitting_view'] = "admission/edit_admission_form";
    $data['main_view'] = 'admission/edit_admission_view';
   
    $this->load->view('layouts/central_template', $data);
   
   
   } else {
      
      
     $admission_update_id = $this->Record_model->get_admission_id_update($admission_id);
   
   
      $data = array(
      'pr_admission_id' => $admission_update_id,   
      'ad_admittedby' => $this->input->post('e_admittedby'),
      'ad_wardname' => $this->input->post('e_wardname'),
      'ad_dischargedate' => $this->input->post('e_discharge'),
      'ad_physician' => $this->input->post('e_physician'),
      'ad_father' => $this->input->post('e_father'),
      'ad_mother' => $this->input->post('e_mother'),
      'ad_chargetoaccount' => $this->input->post('e_chargeofaccount'),
      'ad_relationtopatient' => $this->input->post('e_relationtopatient'),
      'ad_totalpayment' => $this->input->post('e_totalpayment'),
      'ad_address' => $this->input->post('e_address'),
      'ad_number' => $this->input->post('e_number'),
      'ad_complaint' => $this->input->post('e_complain'),
      'ad_completediagnosis' => $this->input->post('e_completediagnosis'),
      'ad_medication' => $this->input->post('e_medication'),
      'ad_conditiontodischarge' => $this->input->post('e_conditiondischarge'),
      'ad_remarks' => $this->input->post('e_remarks')
      
      );
   
      
       if($this->Record_model->update_admission_data($admission_id, $data)){
   
         $data['get_data_admission'] = $this->Record_model->get_data_admission($admission_id);
         
          $data['get_physician'] = $this->Record_model->get_physician();
         $data['title'] = "Edit Admission";
         $data['topbar'] = 'navbar-default';
         $data['admitting_view'] = "admission/edit_admission_form";
         $data['main_view'] = 'admission/edit_admission_view';
     
         $this->session->set_flashdata('edit_admit_success', 'Admission Updated');
         redirect('admissioncontrol/admissionview/'.$admission_id.'#admission', $data);
   
   
       }
   
   }
   
   
   
   }
   
   
   public function admissionview($admission_id){
   
   
       
         $data['get_admission_view'] = $this->Record_model->get_data_admission($admission_id);
   
   
         $data['title'] = "Admission Records";
         $data['topbar'] = 'navbar-default';
         $data['main_view'] = 'admission/admissiondataview';
     
        $this->load->view('layouts/central_template', $data);
     
   
   
   }
   
   
   
   public function addfindingsdataview($findings_id){
   
   $doctor = 'Doctor';
   
   $data['get_user'] = $this->Record_model->get_users_account($doctor);
   $data['get_findings_view'] = $this->Record_model->get_data_findings($findings_id);
   $data['title'] = 'Add to Doctor';
   $data['topbar'] = 'navbar-default';
   $data['form'] = 'admission/addfindingsdataform';
   $data['main_view'] = "admission/addfindingsdata";
   
   $this->load->view('layouts/central_template', $data);
   
   
   }
   
   
   
   public function addadmissiondataview($admission_id){
   $doctor = 'Doctor';
   
   $data['get_user'] = $this->Record_model->get_users_account($doctor);
   $data['get_admission_view'] = $this->Record_model->get_data_admission($admission_id);
   $data['title'] = 'Add to Doctor';
   $data['topbar'] = 'navbar-default';
   $data['form'] = 'admission/addadmissiondataform';
   $data['main_view'] = "admission/addadmissiondata";
   
   $this->load->view('layouts/central_template', $data);
   
   }


















}




 ?>