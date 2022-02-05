<?php
/*
	27/01/22
	Coding test for kaweb.co.uk
	By Andrew Reno
	version 2
*/

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CalcController extends Controller
{
	
	private $cartons, $box, $oq, $total;
	
	function __construct()
	{
		$this->cartons = array(250, 500, 1000, 2000, 5000);
		// $this->cartons = array(2, 3, 5,20);
		$this->box 		= array();
		$this->total = 0;
	}
	
	function GetCartons(){
		return  $this->cartons;
	}
	
	//When the boxes cost more to ship than what's inside ?
 	function OptimisePackages()
 	{	
 		// More widgets vs more boxes? depends on carton size.
 		
 	   // check if boxes can be reduced by checking for 2,
 	   // if 2, then reduce box
 	   for($i = 0; $i < sizeof($this->box)-1;  $i ++)
		if( ! empty($this->box[$this->cartons[$i]]) 
		and $this->box[$this->cartons[$i]] == 2  )
		 {
		 	$this->box[$this->cartons[$i]] -= 2 ;
		 	if(! empty($this->box[$this->cartons[$i+1]] ))
		 		$this->box[$this->cartons[$i+1]] += 1;
		 	else
		 	 	$this->box[$this->cartons[$i+1]]  = 1;
		 }
 
	}
	
	function Recount()
	{
		$this->total = 0;
		foreach($this->cartons as $i => $t)
			$this->total += $this->box[$this->cartons[$i]] * $this->cartons[$i];
		 	
	}
	// Show on the app's view/screen
	public function ShowCartonUnits()
    {
		 $box = $this->cartons;
		 return view('calc')->with( $box );
	}
	
	// Process the user's widget request
    public function ajaxio(Request $r)
    {
		$this->InitWally($r->amount);
		if($r->amount > 0 )
		{	
			$this->Go2($r->amount);
			if(! empty($r->optomise) )
			 $this->OptimisePackages();	 
			 
		}
		
		$this->Recount();
		$this->PrintOut();
	}
	
 	 
	function InitWally($o)
	{
		$this->box 		= array();
		$this->oq		= $o;
		$this->total 	= 0;
		// PHP 8 Save time and less code by initialising
		foreach($this->cartons as $i)
			$this->box[$i] = 0;
	}
 	
 	// Show the results
 	function PrintOut()
 	{	
 		$data = array();
 	    $data['status'] = 0;
 	    $data['msg'] = "";
 		 
		foreach($this->box as $k => $i)
		{
			$data['msg']  .=  $k." x ".$i."<br/>" ; 
			$data['status'] = 1;
		}
		
		 $data['msg']  .= "<div>Total = ".$this->total."</div>";
		 echo json_encode($data);
	}
	
	// Find the optimal number of boxes using rules to send as fewer boxes as possible and as few widgets as possible 
	function  Go2($order_qty)
	{
		 $cartons	= array_reverse($this->cartons);
		 $total 		= 0; 
		  
		 $i = 0;
		 do{
		 		while(1)
		 		{
					
		 			if($total + $cartons[$i] <=  $order_qty)
		 			{
		 				
		 				$this->box[$cartons[$i]] += 1 ;
				 		$total +=  $cartons[$i];
		 					 
		 				 if($total == $order_qty)
		 				 	return; 
		 				elseif ($total > $order_qty) {
							$this->box[$cartons[$i]] -= 1 ;
				 			$total -=  $cartons[$i];
				 			break;
						}		 
			 	
					} else 
						break;
				}
				
			$i ++;
   
		 }while($total < $order_qty and $i < sizeof($cartons));
		 
	  
	 if( $total <  $order_qty)
		{
		 	// echo "Add a 250";
		 	// Smallest carton last to finish off with. 
			if( $this->box[$this->cartons[0]] ) 
			{
				$this->box[$this->cartons[1]]  += 1;

				// Minimal number of boxes
				$this->box[$this->cartons[0]] = 0;
				 
			}else
				$this->box[$this->cartons[0]] = 1;
		}
	 
 
		 
	}
}
