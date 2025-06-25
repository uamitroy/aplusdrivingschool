@extends('layouts.front')
@section('content')
 
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Gallery</h2>

  <div class="breadcr">
    <ul>
      <li><a href="#">Home</a></li>
      <li><i class="fas fa-caret-right"></i></li>
      <li>Gallery</li>
    </ul>
  </div>

</div>

<div class="gap"></div>

<div class="container">

  <div class="text-center">

    <h2 class="heading">About <span class="gradiv padh">US</span></h2>

    <div class="smallarea normalpara">
      <p>A+ Driving School gives you the edge to succeed. We adapt teaching style to suit each individual. Our Primarygoal is to make the learning experience as easy and enjoyable as possible.</p>
    </div>

  </div>

<div class="gap"></div>

<div class="row">

    <div class="col-md-6">

      <div class="lefttotal">

        <div class="firstpic wow animated zoomIn" data-wow-duration="0.5s" data-wow-delay="0.5s"><img class="fullimage" src="{!! asset('design/images/firstpic.jpg') !!}" alt=""/></div>

        <div class="secndpic wow animated bounceInLeft" data-wow-duration="1.5s" data-wow-delay="0.7s"><img class="fullimage" src="{!! asset('design/images/secondpic.jpg') !!}" alt=""/></div>

      </div>

    </div>

    <div class="col-md-6">

      <div class="totalsecond wow animated bounceInRight" data-wow-duration="1s" data-wow-delay="0.7s">
      <h3 class="smallhead">Students will be able to:</h3>

      <div class="normalpara">

      <p>If you're looking for mastering proper driving skills and obtaining your driving certification with confidence, this is the right school.  A+ Driving School is passionate about smarter, safer driving for all students.</p>

      </div>

      <div class="aboutlist">
        <ul>

          <li>Demonstrate practical defensive driving skills</li>
          <li>Demonstrate confidence when driving at night</li>
          <li>Demonstrate how to perform a vehicle per-start check</li>
          <li>Demonstrate how to check and inflate for correct tire pressure</li>
          <li>Demonstrate the confidence and maturity required to drive a vehicle
safely and independently</li>

        </ul>
      </div>
    </div>
    </div>

  </div>
<div class="gap"></div>

<div class="smallarea normalpara text-center botgap60">
      <p>?To gain confidence as you start driving, you need to Practice, practice, practice. It takes time to become a good driver so the more you drive, the more comfortable you will become. We’ll teach you all the driving techniques to become a professional driver.</p>
    </div>

</div>

<div class="gap"></div>
@endsection


