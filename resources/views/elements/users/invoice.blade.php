<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, inital-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

<title>Invoice - A+ Driving School</title>

<style>
  
  body 
{
  color:#383838; 
  background:#fff; 
  font-size:16px; 
  line-height:17px; 
  font-family: 'Roboto', sans-serif;
  padding:0; 
  margin:0;
  position:relative;
}


div, ul, li, a, span, p, h1, h2, h3, h4, h5, h6
{
  padding:0; 
  margin:0; 
  list-style:none;
}

.logopdf
{
  text-align: center;
  margin-top: 45px;
  margin-bottom: 20px;
}

.textpadf
{
  text-align: center;
  margin-bottom: 60px;
}

.textpadf p
{
  line-height: 28px;
}


.wrapper
{
  width: 680px;
  margin:0 auto;
}

.twopartpdf
{
  width: 50%;
  float: left;
}

.twopartpdf p
{
  line-height: 28px;
}
.twopartpdf ul {
	width:50%;
	float:left;
}

.listing p
{
  display: inline-block;
  vertical-align: top;
  line-height: 17px;
  width: 70px;
}

.listing ul
{
  display: inline-block;
  vertical-align: top;
  width: 70%;
}

.listing ul li
{
  display: inline-block;
  padding: 5px 10px;
  /*background: #efeff0; */
  margin: 5px;
  width:44px;
}
.tablesec
{
  padding-top: 50px;
  clear: both;
}
.tablesec table
{
  width: 100%;
  background: #efeff0;
  margin: 0;
  padding: 0;
  border-collapse: collapse;
}
.tablesec tr, td
{
  border: 1px solid #c2c2c2;
  margin: 0;
  padding: 5px 10px;
}


.tablesec td p
{
  padding-bottom: 30px;
}

.msg-break{
  word-wrap: break-word;
}

</style>

</head>
<body>


@if($package->segment->id == 1 || $package->segment->id == 2)
@if($slot->month == 1)
	@php( $month = 'January')
@endif 
@if($slot->month == 2)
	@php( $month = 'February')
@endif 
@if($slot->month == 3)
	@php( $month = 'March')
@endif 
@if($slot->month == 4)
	@php( $month = 'April')
@endif 
@if($slot->month == 5)
	@php( $month = 'May')
@endif 
@if($slot->month == 6)
	@php( $month = 'June')
@endif 
@if($slot->month == 7)
	@php( $month = 'July')
@endif 
@if($slot->month == 8)
	@php( $month = 'August')
@endif 
@if($slot->month == 9)
	@php( $month = 'September')
@endif 
@if($slot->month == 10)
	@php( $month = 'October')
@endif 
@if($slot->month == 11)
	@php( $month = 'November')
@endif   
@if($slot->month == 12)
	@php( $month = 'December')
@endif 
@endif

<div class="logopdf">

  <img src="{!! asset('uploads/'.$setting->logo) !!}" alt="logo"/>

</div>

<div class="textpadf">
  
  <p><b>Address</b>  -  2500 Packard St.Suite 200, Ann Arbor, MI 48104</p>

  <p><b>Phone No.</b>  -  1 (866) 611 - 3552</p>

</div>

<div class="wrapper">

<div class="middlepdf">
  
  <div class="twopartpdf">

      <p><b>Name:</b> {{ $user->fname }} {{ $user->lname }}</p>

      <p><b>Date of Birth:</b> {{ date("d-m-Y", strtotime($user->dob)) }}</p>

      <p><b>Phone No:</b> {{ $user->phone }}</p>

      <p><b>Email:</b> {{ $user->email }}</p>

      <p><b>Address:</b> {{ $user->address }}</p>

  </div>
   @if($package->segment->id == 1 || $package->segment->id == 2)

 	 <div class="twopartpdf">

      <p><b>Class :</b> {{ $package->segment->name }}</p>

      <p><b>Package :</b> {{ $package->name }}</p>
 <p><b>Time : </b>{{ $slot->start_time }} - {{ $slot->end_time }}</p>

 <p><b>Year : </b> {{ $slot->year }}</p>	
	  <p><b>Month : </b> {{ $month }}</p>
    <p><b>Type : </b> {{ $slot->type }}</p>
	  
      <div class="listing">

	 
 <p><b>Dates :</b>
        @php( $date_array = explode(",",$slot->dates))
		<ul>
		  @for($dt = 0 ; $dt < count($date_array); $dt++)
			<li>{{ $date_array[$dt] }}</li>
		  @endfor
		</ul>
		<br />
      </div>
</p>
     
      

  </div>
  
  @else
  
  	 <div class="twopartpdf">

		  <p><b>Class :</b> {{ $package->segment->name }}</p>
	
		  <p><b>Package : {{ $package->name }}</b></p>

   	 </div>
	 
  @endif
</div>

<div style="clear:both;"></div>
<p><br /></p>
<p><br /></p>
<p><br /></p>
<p><br /></p>
<p><br /></p>
<p><br /></p>
<div class="tablesec">

<table width="100%">
  
  <tr>

    <td width="10%"><b>Date Of Tx :</b></td>
    <td width="50%"><b>Transaction ID :</b></td>
	<td width="20%"><b>Gateway :</b></td>
    <td width="20%"><b>Charges :</b></td>

  </tr>

  <tr>
    <td width="10%">
      
      <p>{{ date("m-d-Y", strtotime($transaction->created_at)) }}</p>

    </td>

    <td width="50%">
  
	  <p class="msg-break">{{ $transaction->tx_id }}</p>
      
    </td>
	<td width="20%">
        <p>{{ $transaction->gateway }}</p>
    </td>

    <td width="20%">
		@if($package->price)
			<p>${{ $package->price }}</p>
		@else
			<p>${{ $transaction->amount }}</p>
		@endif
        
		@if($addtional_info == 1)
        <p>$50.00</p>
		@endif
    </td>

  </tr>


<tr>

    <td><b></b></td>
	<td><b></b></td>
    <td><b>total</b></td>
    <td><b>${{ $transaction->amount }}</b></td>

  </tr>



</table>
@if($package->segment->id == 1 || $addtional_info == 1)
<div class="textpadf">
  <p><b>*** Note : </b> You can Select Slot of Segment Two From Transaction Details (Dashboard) for an additional $50</p>
</div>
@endif
</div>

</div>

</body>
</html>
