<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Segment;
use App\Slot;
use Session;


class SlotController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
     public function index() {

        $slots = Slot::with('segment:id,name')->orderBy('created_at', 'desc')->get();
        return view('admin.slots.index', compact('slots'));
    }

    public function create() {

        $segments = Segment::pluck('name', 'id')->toArray();
        $selected_segment = null;
        return view('admin.slots.create', compact('segments', 'selected_segment'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'segment_id' => 'required|integer',
            'type' => 'required|in:Online,Offline',
            'year'       => 'required|integer',
            'month'       => 'required|integer',
            'start_time'       => 'required',
            'end_time'       => 'required',
            'dates'       => 'required|string',
            'seat_allotted' => 'required|integer'
        ]);

        $input = $request->all();
        $input['dates'] = str_replace('/'.$request->year, '', strtolower($request->dates));
        $result = Slot::create($input);
        if ($result) {
            Session::flash('success', 'Slot created successfully');
        } else {
            Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.slots.list');
    }

    public function edit($id) {

        $slot = Slot::findOrFail($id);
        $segments = Segment::pluck('name', 'id')->toArray();
        $selected_segment = $slot->segment->id;
        $dates = str_replace(',', '/'.$slot->year.',', strtolower($slot->dates));
        $dates = $dates.'/'.$slot->year;
        return view('admin.slots.edit', compact('slot', 'segments', 'selected_segment','dates'));
    }

    public function update(Request $request, $id) {


        $this->validate($request, [
            'segment_id' => 'required|integer',
            'type' => 'required|in:Online,Offline',
            'year'       => 'required|integer',
            'month'       => 'required|integer',
            'start_time'       => 'required',
            'end_time'       => 'required',
            'dates'       => 'required|string',
            'seat_allotted' => 'required|integer'
        ]);

        $slot = Slot::findOrFail($id);
        $input = $request->all();
        $input['dates'] = str_replace('/'.$request->year, '', strtolower($request->dates));
        $result = $slot->update($input);
        if ($result) {
            Session::flash('success', 'Slot updated successfully');
        } else {
            Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.slots.list');
    } 

    public function destroy($id) {

        $result = Slot::findOrFail($id)->delete();
        if($result){
           Session::flash('success', 'Slot deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.slots.list');
    }  

    public function search(Request $request){

        $segment_id = $request->selectedSegment;
        $year = $request->selectedYear;
        $month = $request->selectedMonth;

        $monthArr = ['1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        $slots = Slot::when($segment_id, function($q) use($segment_id){
                    return $q->where('segment_id', '=', $segment_id);
                })->when($year, function($q) use($year){
                    return $q->where('year', '=', $year);
                })->when($month, function($q) use($month){
                    return $q->where('month', '=', $month);
                })->orderBy('created_at', 'desc')->get();

        $contentHtml = '<option value="">Select Slots</option>';
        foreach ($slots as $key => $slot) {
            $contentHtml .= '<option value="'. $slot->id .'">'. $slot->segment->name . ' - ' .$slot->year .' - '. $monthArr[$slot->month] . ' - ' . $slot->start_time . ' - ' . $slot->end_time .'</option>';
        }

        return response()->json($contentHtml);

    }

}
