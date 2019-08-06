<div class="contents">
	<div class="loading_wp_over"></div>
	<div class="row">
		<div class="col-12">
			<?php
				$msgsuccess = $this->session->flashdata('msgsuccess');
				if(isset($msgsuccess) && $msgsuccess != ''){
					echo '<div class="alert bg-success"><span class="closebtn">&times;</span>'.$msgsuccess.'</div>';
					echo '<script>setTimeout(function(){$(".closebtn").click()}, 5000);</script>';
					$this->session->set_flashdata('msgsuccess', '');
				}

				$msgerror = $this->session->flashdata('msgerror');
				if(isset($msgerror) && $msgerror != ''){
					echo '<div class="alert bg-warning"><span class="closebtn">&times;</span>'.$msgerror.'</div>';
					echo '<script>setTimeout(function(){$(".closebtn").click()}, 5000);</script>';
					$this->session->set_flashdata('msgerror', '');
				}
			?>
		</div>
