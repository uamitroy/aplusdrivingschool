@push('js')
@if(Session::has('success'))
	<script type="text/javascript">
		 $(document).ready(function(){
	
			 demo.initChartist();
	
			 $.notify({
				 message: "{{ Session::get('success') }}"
	
				},{
					type: 'success',
					timer: 4000
				});
	
		 });
	   </script>
@endif
@if(Session::has('info'))
	<script type="text/javascript">
		 $(document).ready(function(){
	
			 demo.initChartist();
	
			 $.notify({
				 message: "{{ Session::get('info') }}"
	
				},{
					type: 'info',
					timer: 4000
				});
	
		 });
	   </script>
@endif
@if(Session::has('warning'))
	<script type="text/javascript">
		 $(document).ready(function(){
	
			 demo.initChartist();
	
			 $.notify({
				 message: "{{ Session::get('warning') }}"
	
				},{
					type: 'warning',
					timer: 4000
				});
	
		 });
	   </script>
@endif
@if(Session::has('danger'))
	<script type="text/javascript">
		 $(document).ready(function(){
	
			 demo.initChartist();
	
			 $.notify({
				 message: "{{ Session::get('danger') }}"
	
				},{
					type: 'danger',
					timer: 4000
				});
	
		 });
	   </script>
@endif
@endpush
