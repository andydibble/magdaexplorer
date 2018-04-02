<?php App::import('Utility', 'CakeTime'); ?>

<h2>Logins</h2>

<table id="listThemes" style="clear:both">	
	<tr>
		<?php if($isAdmin): ?>
		<th width="7%">Delete?</th>
		<?php endif; ?>
		<th>Venue</th>
		<th>Locale</th>		
		<th width="22%">Map</th>		
		<th class="updatedCol"><?php echo $this->Paginator->sort('Reference.date_created', 'Date'); ?></th>		
	</tr>		
	<?php foreach($logins as $i => $login): ?>		
		<tr>						
			<?php if($isAdmin): ?>
			<td><?php echo $this->Form->input('delete', array('type' => 'checkbox', 'class' => 'delete', 'label' => '', 'value' => $login['id'])); ?></td>
			<?php endif; ?>
			<td>
				<?php echo $login['venue']; ?>				
			</td>
			<td>
				<?php echo $login['city'].', '.$login['region'].', '.$login['country']; ?>				
			</td>			
			<td>
				<div id="map-<?php echo $login['id']?>" style="width:190px; height:190px; border: 1px solid #444">
				
				</div>				
			</td>
			<td>
				<?php echo CakeTime::niceShort($login['date']); ?>				
			</td>
		</tr>			
	<?php endforeach; ?>
</table>

<?php echo $this->Element('paging', array('model' => 'Login'))?>


<script>
      function initMap() {
        var loc = {lat: parseFloat(explorerLoc.latitude), lng: parseFloat(explorerLoc.longitude)};
				
        var map = new google.maps.Map(document.getElementById('check-in-location-map'), {
          zoom: 10,
          center: loc
        });
        var marker = new google.maps.Marker({
          position: loc,
          map: map
        });
      }
 </script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdTgvWsM2hWoz-BEYMOo3FFjpxmRWPB_0&callback=initMap">
</script>

<script>
var logins = <?php echo json_encode($logins) ?>;	
$(document).ready(function () {		
	$(".delete").change(function(event) {		
		$.confirm(			
			'Are you sure you want to delete this Login?',
			function(result) {
				if (result) {
					$.ajax({
						type: "GET",				
						url: APPROOT+'logins/delete',
						dataType: 'json',
						data: {id: event.target.value},
						contentType: "application/json; charset=utf-8",	    			
						success: function(data) {																							
							window.location = window.location;									   				
						}    			
					});
				}
			}
		);		
	});  
});

function initMap() {
//get maps from lat/long of each login
	
	$(logins).each(function(index, value) {				
		var loc = {lat: parseFloat(value.latitude), lng: parseFloat(value.longitude)};
				
        var map = new google.maps.Map(document.getElementById('map-'+value.id), {
          zoom: 10,
          center: loc
        });
        var marker = new google.maps.Marker({
          position: loc,
          map: map
        });	    	    	    
	});  
}
        
</script>