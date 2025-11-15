<?php if($status == 0){ ?>
    <div class="badge bg-warning">waiting</div>
<?php	} else if($status == 1){ ?>
 	<div class="badge bg-danger">Rejected</div>
<?php 	} else if($status == 2){ ?>
 	<div class="badge bg-success">Approved</div>
<?php 	
	}
?>