<?php
namespace Drupal\iastoreimport\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/** 
 *  IA Store Import Controller
 */
class IastorelocatorController extends ControllerBase{
	/*
	 * Return message of successful import
	 */ 
	public function content()
	{
		/*
		 * Read CSV file
		 */
		
		//Enter correct path of file
		$file = getcwd()."/storescsv/example.csv";
		
		$row = 1; $key = 0;
		$data = array();
		if (($handle = fopen($file, "r")) !== FALSE) {
		  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		    		    //echo "<p> $num fields in line $row: <br /></p>\n";
		    if($row==1)
		    {
		    	$row++;
		    	continue;
		    }
		    
		    //make array as per your CSV array
		    $dataArr[$key]['id'] = $data[0];
		    $dataArr[$key]['state'] = $data[1];
		    $dataArr[$key]['city'] = $data[2];
		    $dataArr[$key]['area'] = $data[3];
		    $dataArr[$key]['franchisee'] = $data[4];
		    $dataArr[$key]['type'] = $data[5];
		    $dataArr[$key]['mobile'] = $data[6];
		    $dataArr[$key]['lat'] = $data[7];
		    $dataArr[$key]['long'] = $data[8];
		    $dataArr[$key]['address'] = $data[9];
		    $dataArr[$key]['is_booked'] = $data[10];

		    $key++;
		  }
		  fclose($handle);
			
			//Create nodes programatically and insert in correct content type
		    //In below example, content type = store_locator
		    //set field values as per your content type
			foreach($dataArr as $obj)
			{
				$node = Node::create(['type'=>'store_locator']);
				$node->set('title',rtrim(trim($obj['area']),','));
				$node->set('body',array('value'=>$obj['address'],'format'=>'basic_html'));
				$node->set('field_city',$obj['city']);
				$node->set('field_is_booked',$obj['is_booked']);
				$node->set('field_latitude',$obj['lat']);
				$node->set('field_longitude',$obj['long']);
				$node->set('field_mobile',$obj['mobile']);
				$node->set('field_state',$obj['state']);
				$node->set('field_type',$obj['type']);
				$node->set('field_franchisee',$obj['franchisee']);
				


				$node->enforceIsNew();
				$node->save();
				//break;
			}
			$message = "Data imported successfully !!";
		}
		else{
			$message = "File not found !!";
		}
		

		$build = [
			'#title' => $message,
			'#markup' => $this->t('')
		];
		return $build;
	}
}


?>