<script src = "<?php echo base_url(); ?>assets/js/scripts/bootstrap.js"></script>
<script src = "<?php echo base_url(); ?>assets/js/scripts/jquery.min.js"></script>
<script src = "<?php echo base_url(); ?>assets/js/scripts/dropdown.js"></script>
<script src = "<?php echo base_url(); ?>assets/js/scripts/sidebar.js"></script>
<script src = "<?php echo base_url(); ?>assets/js/scripts/jquery.dataTables.js"></script>
<script src = "<?php echo base_url(); ?>assets/js/scripts/custom.js"></script>
<script type = "text/javascript">
	//Modified Binary Algorithm
	$(document).ready(function() {
		$low = 0;
   		$high = count($arr) - 1;
   		while ($low <= $high) {
      		$mid = floor(($low + $high) / 2);
      		if($arr[$mid] == $data) {
				$('#table').DataTable();
				$('#table1').DataTable();
      		}
      		if($arr[$low] == $data) {
				$('#table').DataTable();
				$('#table1').DataTable();
      		}
      		if($arr[$high] == $data) {
				$('#table').DataTable();
				$('#table1').DataTable();
      		}
      		if ($x < $arr[$mid]) {
         		$high = $mid -1;
      		}
      		else {
         		$low = $mid + 1;
      		}
  		}	
	});
</script>
