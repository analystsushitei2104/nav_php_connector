<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="is-IS">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>NAV Web Service Examples via PHP</title>
</head>
<body>

<?php
require_once("ntlm/NTLMStream.php");
require_once("ntlm/NTLMSoapClient.php");
stream_wrapper_unregister('http');
stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");

//Ensure you can get a list of services by entering the Services URL in a Web Browser - No Point Continuing until you verify that The Web Services is running.
$servicesURL = 'http://nav.sushitei.co.id:7017/SushiteiTest/WS/Services';
$baseURL = 'http://nav.sushitei.co.id:7017/SushiteiTest/WS/'; 
$CompanyName = "SushiTei2019"; 
$now = date('Y-m-d');
//>>>>>>>>>>>>>>>>>Item Query>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
function poline($id) 
{
	//echo $id;
	//echo "<br>";
	$pageURL = $GLOBALS['baseURL'].rawurlencode($GLOBALS['CompanyName']).'/Page/PurchOrderLineNav';
	$service = new NTLMSoapClient($pageURL);
	$params = array('filter'=>array(array('Field' => 'Document_No', 'Criteria' => 'PO-HO-0619-1792')), 
	'setSize' => '10');
	$result = $service->ReadMultiple($params); 
	$resultSet = $result->ReadMultiple_Result->PurchOrderLineNav;
	
	//return $resultSet;
	$poline[$id] = array();
	foreach ($resultSet as $b)
	{	
		$noPO = $b->Document_No ?? NULL ;
		$item = $b->No ?? NULL;
		$qty = $b->Quantity ?? NULL;
		$uOM = $b->Unit_of_Measure ?? NULL;
		$poline2 = array(
			'No_PO' 	=> $noPO,
			'itemCode' 	=> $item,
			'qty' 		=> $qty,
			'uOM'		=> $uOM
		);
		array_push($poline[$id],$poline2);
		//echo $b->No ?? NULL;
		//echo $b->Quantity ?? NULL;
		//echo $b->Unit_of_Measure ?? NULL;
	}
	return $poline;
	/*
	echo json_encode($resultSet);
	foreach ($resultSet as $b)
	{
		echo $b->Parent_Item_No ?? NULL;
		echo '&nbsp';
		echo $b->No ?? NULL;
		echo '&nbsp';
		echo $b->Quantity_per  ?? NULL;
		echo '&nbsp';
		echo $b->Unit_of_Measure_Code?? NULL;
		echo '&nbsp';
		echo $b->Quantity_Waste?? NULL;
		echo '<br>';
	}
	*/
}

if(isset($_GET['master']))
{
	$mastercard = $_GET['master'];
	try 
	{
		if($mastercard == 'vendor')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/VendorCard';
			$service = new NTLMSoapClient($pageURL);
			$params = array('filter' => array(array('Field' => 'Code', 'Criteria' => "<>''")), 
							'setSize' => ''); 
			$result = $service->ReadMultiple($params); 
			$resultSet = $result->ReadMultiple_Result->VendorCard;
			foreach ($resultSet as $v)
			{
				echo $v->No;
				echo '&nbsp';
				echo $v->Name ?? null;
				echo '&nbsp';
				echo $v->Address ?? null;
				echo '&nbsp';
				echo $v->Blocked ?? null;
				echo '<br>';
			}
		}
		elseif($mastercard == 'uom')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/UOM';
			$service = new NTLMSoapClient($pageURL);
			$params = array('filter' => array(array('Field' => 'Code', 'Criteria' => "<>''")), 
							'setSize' => ''); 
			$result = $service->ReadMultiple($params); 
			$resultSet = $result->ReadMultiple_Result->UOM;
			foreach ($resultSet as $uom)
			{
				echo $uom->Code;
				echo '&nbsp';
				echo $uom->Description ?? null;
				echo '<br>';
			}
		}
		if($mastercard == 'item')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/ItemCard';
			$service = new NTLMSoapClient($pageURL);
			/*Filter by Item Code
			$params = array('filter'=>array(array('Field' => 'No', 'Criteria' => '')), 
													'setSize' => '100');
			*/
			/* Filter by Last Input / Modified */
			$params = array('filter'=>array(array('Field' => 'Last_Date_Modified', 'Criteria' => '')), 
													'setSize' => '100');
			$result = $service->ReadMultiple($params); 
			$resultSet = $result->ReadMultiple_Result->ItemCard;
			//echo json_encode($resultSet);
			foreach ($resultSet as $item)
			{
				echo $item->No;
				echo '&nbsp';
				echo $item->Description  ?? NULL;
				echo '&nbsp';
				echo $item->Item_Category_Code ?? NULL;
				echo '&nbsp';
				echo $item->Base_Unit_of_Measure ?? NULL;
				echo '&nbsp';
				echo $item->Blocked ?? NULL;
				echo '<br>';
			}
		}
		if($mastercard == 'conversion')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/UnitConversion';
			$service = new NTLMSoapClient($pageURL);
			/*Filter by Item Code
			$params = array('filter'=>array(array('Field' => 'No', 'Criteria' => '')), 
													'setSize' => '100');
			*/
			/* Filter by Last Input / Modified */
			$params = array('filter'=>array(array('Field' => 'Last_Date_Modified', 'Criteria' => '')), 
													'setSize' => '100');
			$result = $service->ReadMultiple($params); 
			$resultSet = $result->ReadMultiple_Result->UnitConversion;
			echo json_encode($resultSet);
			foreach ($resultSet as $c)
			{
				echo $c->Item_No ?? NULL;
				echo '&nbsp';
				echo $c->Code  ?? NULL;
				echo '&nbsp';
				echo $c->Qty_per_Unit_of_Measure ?? NULL;
				echo '<br>';
			}
		}
		if($mastercard == 'bom')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/BoM';
			$service = new NTLMSoapClient($pageURL);
			/*Filter by Item Code
			$params = array('filter'=>array(array('Field' => 'No', 'Criteria' => '')), 
													'setSize' => '100');
			*/
			/* Filter by Last Input / Modified */
			$params = array('filter'=>array(array('Field' => 'Last_Date_Modified', 'Criteria' => '')), 
													'setSize' => '100');
			$result = $service->ReadMultiple($params); 
			$resultSet = $result->ReadMultiple_Result->BoM;
			echo json_encode($resultSet);
			foreach ($resultSet as $b)
			{
				echo $b->Parent_Item_No ?? NULL;
				echo '&nbsp';
				echo $b->No ?? NULL;
				echo '&nbsp';
				echo $b->Quantity_per  ?? NULL;
				echo '&nbsp';
				echo $b->Unit_of_Measure_Code?? NULL;
				echo '&nbsp';
				echo $b->Quantity_Waste?? NULL;
				echo '<br>';
			}
		}
		if($mastercard == 'po')
		{

			$pageURL = $baseURL.rawurlencode($CompanyName).'/Codeunit/MasterData';
			$service = new NTLMSoapClient($pageURL);
			$params = array('pO' => '');
			$result = $service->ExportPO($params); 
			$resultSet = $result->pO->POHeader;
			foreach ($resultSet as $b)
			{
				echo json_encode($b);
			};
			/*
			foreach ($resultSet as $b)
			{
				$PO = $b->PO_No ?? NULL;
				$pageURL2 = poline($PO);
				//$service2 = new NTLMSoapClient($pageURL2);
				//$params2 = array('docNo' => $b->PO_No);
				//$result2 = $service2->ExportPOLine($params2); 
				//$resultSet2 = $result2;
				echo json_encode($pageURL2);
				echo "<br>";
			}
			*/

			//echo json_encode($resultSet);
			/*
			foreach ($resultSet as $b)
			{
				echo $b->PO_No ?? NULL;
				$pageURL2 = $baseURL.rawurlencode($CompanyName).'/Codeunit/NAVSync';
				$service2 = new NTLMSoapClient($pageURL2);
				$params2 = array('docNo' => $b->PO_No);
				$result2 = $service2->ExportPOLine($params2); 
				$resultSet2 = $result2;
				echo json_encode($resultSet2);
				echo "<br>";
			}
			*/
		}
		if($mastercard == 'poline')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/PurchOrderLineNav';
			$service = new NTLMSoapClient($pageURL);
			$params = array('filter'=>array(array('Field' => 'No', 'Criteria' => '')), 
			'setSize' => '100');
			$result = $service->ReadMultiple($params); 
			$resultSet = $result->ReadMultiple_Result->PurchOrderLineNav;
			echo json_encode($resultSet);
			foreach ($resultSet as $b)
			{
				echo $b->Parent_Item_No ?? NULL;
				echo '&nbsp';
				echo $b->No ?? NULL;
				echo '&nbsp';
				echo $b->Quantity_per  ?? NULL;
				echo '&nbsp';
				echo $b->Unit_of_Measure_Code?? NULL;
				echo '&nbsp';
				echo $b->Quantity_Waste?? NULL;
				echo '<br>';
			}
			
		}
		if($mastercard == 'to2')
		{
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Codeunit/NAVSync';
			$service = new NTLMSoapClient($pageURL);
			$params = array(
				'docNo'=> 'COBACOBA',
				'loc_Fr'=> '002',
				'loc_To'=> '015',
				'desc_To'=> 'COBA',
				'shipDate'=> '2019-08-08',
				'rec_Date'=> '2019-08-08',
				'tONotes'=> '2019-08-08',
				'item_Code'=> 'L140',
				'uoM'=> 'GR',
				'qty_TO'=> '1000',
			);
			
			$result = $service->CreateTOHeader($params); 
			echo $result;
			//$resultSet = $result->ReadMultiple_Result->PurchOrderLineNav;
			//echo json_encode($resultSet);
		}
		if($mastercard == 'po2')
		{
			$servicesURL = 'http://nav.sushitei.co.id:7017/SushiteiTest/WS/Services';
			$baseURL = 'http://nav.sushitei.co.id:7017/SushiteiTest/WS/'; 
			$CompanyName = "SushiTei2019"; 
			$pageURL = $baseURL.rawurlencode($CompanyName).'/Page/PurchOrderNav';
			$service = new NTLMSoapClient($pageURL);

			/*
				# Release Status dan Belum terambil datanya
				# Data akan ditarik per 100 No. Purchase Order (Set Size)
			*/

			$params = array('filter'=>array(
							array('Field' => 'Status', 'Criteria' => '1'),
							array('Field' => 'Rec_Stat', 'Criteria' => '0')
							), 
							'setSize' => '100');
			$result = $service->ReadMultiple($params);
			if (!empty($result))
			{
				$resultSet = $result->ReadMultiple_Result->PurchOrderNav;
				foreach ($resultSet as $hasil2) {

				$Key = $hasil2->Key ?? NULL;
				$No = $hasil2->No ?? NULL;
				$Buy_from_Vendor_No = $hasil2->Buy_from_Vendor_No ?? NULL;
				$Order_Date = $hasil2->Order_Date ?? NULL;
				$PurchLines = $hasil2->PurchLines ?? NULL;
				
				if (is_array($PurchLines->Purchase_Order_Line))
				{
					foreach ($PurchLines->Purchase_Order_Line as $item)
					{
						/*
							# Hanya menampilkan Jenis Item adalah Stock
						*/
						if(($item->Type) !== 'G_L_Account' )
						{
							echo 'Document No. : '.$item->Document_No;
							echo 'Item Code : '.$item->No;
							echo 'Quantity : '.$item->Quantity;
							echo 'UoM : '.$item->Unit_of_Measure_Code;
							echo 'PR : '.$item->PR_No;
						}
					}
				}
				else
				{
						/*
							# Hanya menampilkan Jenis Item adalah Stock
						*/
					if(($PurchLines->Purchase_Order_Line->Type) !== 'G_L_Account' )
					{
						echo 'Document No. : '.$PurchLines->Purchase_Order_Line->Document_No.'<br>';
						echo 'Item Code : '.$PurchLines->Purchase_Order_Line->No.'<br>';
						echo 'Quantity : '.$PurchLines->Purchase_Order_Line->Quantity.'<br>';
						echo 'UoM : '.$PurchLines->Purchase_Order_Line->Unit_of_Measure_Code.'<br>';
						echo 'PR : '.$PurchLines->Purchase_Order_Line->PR_No.'<br>';
					}
				}
				$params2 = array('PurchOrderNav'=>
				array(
					'Key'=> $Key,
					'No'=> $No,
					'Rec_Stat'=> '1',
				));
				$result2 = $service->Update($params2);
				}
			}
		}
	}
	catch (Exception $e) 
	{
    	echo "<hr><b>ERROR: SoapException:</b> [".$e."]<hr>";
		echo "<pre>".htmlentities(print_r($service->__getLastRequest(),1))."</pre>";
	}
}




?>

</body>
</html>

