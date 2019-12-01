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
			if (!empty($result)){
			$resultSet = $result->ReadMultiple_Result->PurchOrderNav;
			echo json_encode($resultSet);
			foreach ($resultSet as $hasil2) {
				$No = $hasil2->No ?? NULL;
				$Buy_from_Vendor_No = $hasil2->Buy_from_Vendor_No ?? NULL;
				$Buy_from_Contact_No = $hasil2->Buy_from_Contact_No ?? NULL;
				$Buy_from_Vendor_Name = $hasil2->Buy_from_Vendor_Name ?? NULL;
				$Buy_from_Address = $hasil2->Buy_from_Address ?? NULL;
				$Buy_from_Address_2 = $hasil2->Buy_from_Address_2 ?? NULL;
				$Buy_from_Post_Code = $hasil2->Buy_from_Post_Code ?? NULL;
				$Buy_from_City = $hasil2->Buy_from_City ?? NULL;
				$Buy_from_Contact = $hasil2->Buy_from_Contact ?? NULL;
				$Posting_Date = $hasil2->Posting_Date ?? NULL;
				$Order_Date = $hasil2->Order_Date ?? NULL;
				$Document_Date = $hasil2->Document_Date ?? NULL;
				$Vendor_Shipment_No = $hasil2->Vendor_Shipment_No ?? NULL;
				$Vendor_Invoice_No = $hasil2->Vendor_Invoice_No ?? NULL;
				$Order_Address_Code = $hasil2->Order_Address_Code ?? NULL;
				$Purchaser_Code = $hasil2->Purchaser_Code ?? NULL;
				$Location_Code = $hasil2->Location_Code ?? NULL;
				$Assigned_User_ID = $hasil2->Assigned_User_ID ?? NULL;
				$Due_Date = $hasil2->Due_Date ?? NULL;
				$Status = $hasil2->Status ?? NULL;
				$Close_Reason = $hasil2->Close_Reason ?? NULL;
				$Receiving_No_Series = $hasil2->Receiving_No_Series ?? NULL;
				$Posting_No_Series = $hasil2->Posting_No_Series ?? NULL;
				$Shortcut_Dimension_1_Code = $hasil2->Shortcut_Dimension_1_Code ?? NULL;
				$Shortcut_Dimension_2_Code = $hasil2->Shortcut_Dimension_2_Code ?? NULL;
				$Check_Invoice_Date = $hasil2->Check_Invoice_Date ?? NULL;
				$Add_Supplier_Invoice_No = $hasil2->Add_Supplier_Invoice_No ?? NULL;
				$No_Printed = $hasil2->No_Printed ?? NULL;
				$Notes = $hasil2->Notes ?? NULL;
				$Payment_Date = $hasil2->Payment_Date ?? NULL;
				$Receive = $hasil2->Receive ?? NULL;
				$Expected_Receipt_Date = $hasil2->Expected_Receipt_Date ?? NULL;
				$Create_Date = $hasil2->Create_Date ?? NULL;
				$Modified_By = $hasil2->Modified_By ?? NULL;
				$No_Series = $hasil2->No_Series ?? NULL;
				$Currency_Code = $hasil2->Currency_Code ?? NULL;
				$Created_By = $hasil2->Created_By ?? NULL;
				$Vendor_Over_Limit = $hasil2->Vendor_Over_Limit ?? NULL;
				$Pay_to_Vendor_No = $hasil2->Pay_to_Vendor_No ?? NULL;
				$Pay_to_Contact_No = $hasil2->Pay_to_Contact_No ?? NULL;
				$Pay_to_Name = $hasil2->Pay_to_Name ?? NULL;
				$Pay_to_Address = $hasil2->Pay_to_Address ?? NULL;
				$Pay_to_Address_2 = $hasil2->Pay_to_Address_2 ?? NULL;
				$Pay_to_Post_Code = $hasil2->Pay_to_Post_Code ?? NULL;
				$Pay_to_City = $hasil2->Pay_to_City ?? NULL;
				$Pay_to_Contact = $hasil2->Pay_to_Contact ?? NULL;
				$Payment_Terms_Code = $hasil2->Payment_Terms_Code ?? NULL;
				$Payment_Discount_Percent = $hasil2->Payment_Discount_Percent ?? NULL;
				$Pmt_Discount_Date = $hasil2->Pmt_Discount_Date ?? NULL;
				$Payment_Method_Code = $hasil2->Payment_Method_Code ?? NULL;
				$Payment_Reference = $hasil2->Payment_Reference ?? NULL;
				$Creditor_No = $hasil2->Creditor_No ?? NULL;
				$On_Hold = $hasil2->On_Hold ?? NULL;
				$Prices_Including_VAT = $hasil2->Prices_Including_VAT ?? NULL;
				$VAT_Bus_Posting_Group = $hasil2->VAT_Bus_Posting_Group ?? NULL;
				$Ship_to_Name = $hasil2->Ship_to_Name ?? NULL;
				$Ship_to_Address = $hasil2->Ship_to_Address ?? NULL;
				$Ship_to_Address_2 = $hasil2->Ship_to_Address_2 ?? NULL;
				$Ship_to_Post_Code = $hasil2->Ship_to_Post_Code ?? NULL;
				$Ship_to_City = $hasil2->Ship_to_City ?? NULL;
				$Ship_to_Contact = $hasil2->Ship_to_Contact ?? NULL;
				$Inbound_Whse_Handling_Time = $hasil2->Inbound_Whse_Handling_Time ?? NULL;
				$Shipment_Method_Code = $hasil2->Shipment_Method_Code ?? NULL;
				$Lead_Time_Calculation = $hasil2->Lead_Time_Calculation ?? NULL;
				$Requested_Receipt_Date = $hasil2->Requested_Receipt_Date ?? NULL;
				$Promised_Receipt_Date = $hasil2->Promised_Receipt_Date ?? NULL;
				$Sell_to_Customer_No = $hasil2->Sell_to_Customer_No ?? NULL;
				$Ship_to_Code = $hasil2->Ship_to_Code ?? NULL;
				$Modified_Time = $hasil2->Modified_Time ?? NULL;
				$Modified_Date = $hasil2->Modified_Date ?? NULL;
				$Transaction_Type = $hasil2->Transaction_Type ?? NULL;
				$Transaction_Specification = $hasil2->Transaction_Specification ?? NULL;
				$Transport_Method = $hasil2->Transport_Method ?? NULL;
				$Entry_Point = $hasil2->Entry_Point ?? NULL;
				$Area = $hasil2->Area ?? NULL;
				$Prepayment_Percent = $hasil2->Prepayment_Percent ?? NULL;
				$Compress_Prepayment = $hasil2->Compress_Prepayment ?? NULL;
				$Prepmt_Payment_Terms_Code = $hasil2->Prepmt_Payment_Terms_Code ?? NULL;
				$Prepayment_Due_Date = $hasil2->Prepayment_Due_Date ?? NULL;
				$Prepmt_Payment_Discount_Percent = $hasil2->Prepmt_Payment_Discount_Percent ?? NULL;
				$Prepmt_Pmt_Discount_Date = $hasil2->Prepmt_Pmt_Discount_Date ?? NULL;
				$Vendor_Cr_Memo_No = $hasil2->Vendor_Cr_Memo_No ?? NULL;

				$params2 = array('PurchOrderNav'=>
						array(
							'Key'=> $Key,
							'No'=> $No,
							'Status'=> '1',
							//'Buy_from_Vendor_No'=> 'A005'
							/*
							$Buy_from_Vendor_No,
							'Buy_from_Contact_No'=> $Buy_from_Contact_No,
							'Buy_from_Vendor_Name'=> $Buy_from_Vendor_Name,
							'Buy_from_Address'=> $Buy_from_Address,
							'Buy_from_Address_2'=> $Buy_from_Address_2,
							'Buy_from_Post_Code'=> $Buy_from_Post_Code,
							'Buy_from_City'=> $Buy_from_City,
							'Buy_from_Contact'=> $Buy_from_Contact,
							'Posting_Date'=> $Posting_Date,
							'Order_Date'=> $Order_Date,
							'Document_Date'=> $Document_Date,
							'Vendor_Shipment_No'=> $Vendor_Shipment_No,
							'Vendor_Invoice_No'=> $Vendor_Invoice_No,
							'Order_Address_Code'=> $Order_Address_Code,
							'Purchaser_Code'=> $Purchaser_Code,
							'Location_Code'=> $Location_Code,
							'Assigned_User_ID'=> $Assigned_User_ID,
							'Due_Date'=> $Due_Date,
							'Status'=> $Status,
							'Close_Reason'=> $Close_Reason,
							'Receiving_No_Series'=> $Receiving_No_Series,
							'Posting_No_Series'=> $Posting_No_Series,
							'Shortcut_Dimension_1_Code'=> $Shortcut_Dimension_1_Code,
							'Shortcut_Dimension_2_Code'=> $Shortcut_Dimension_2_Code,
							'Check_Invoice_Date'=> $Check_Invoice_Date,
							'Add_Supplier_Invoice_No'=> $Add_Supplier_Invoice_No,
							'No_Printed'=> $No_Printed,
							'Notes'=> $Notes,
							'Payment_Date'=> $Payment_Date,
							'Receive'=> $Receive,
							'Expected_Receipt_Date'=> $Expected_Receipt_Date,
							'Create_Date'=> $Create_Date,
							'Modified_By'=> $Modified_By,
							'No_Series'=> $No_Series,
							'Currency_Code'=> $Currency_Code,
							'Created_By'=> $Created_By,
							'Vendor_Over_Limit'=> $Vendor_Over_Limit,
							'Pay_to_Vendor_No'=> $Pay_to_Vendor_No,
							'Pay_to_Contact_No'=> $Pay_to_Contact_No,
							'Pay_to_Name'=> $Pay_to_Name,
							'Pay_to_Address'=> $Pay_to_Address,
							'Pay_to_Address_2'=> $Pay_to_Address_2,
							'Pay_to_Post_Code'=> $Pay_to_Post_Code,
							'Pay_to_City'=> $Pay_to_City,
							'Pay_to_Contact'=> $Pay_to_Contact,
							'Payment_Terms_Code'=> $Payment_Terms_Code,
							'Payment_Discount_Percent'=> $Payment_Discount_Percent,
							'Pmt_Discount_Date'=> $Pmt_Discount_Date,
							'Payment_Method_Code'=> $Payment_Method_Code,
							'Payment_Reference'=> $Payment_Reference,
							'Creditor_No'=> $Creditor_No,
							'On_Hold'=> $On_Hold,
							'Prices_Including_VAT'=> $Prices_Including_VAT,
							'VAT_Bus_Posting_Group'=> $VAT_Bus_Posting_Group,
							'Ship_to_Name'=> $Ship_to_Name,
							'Ship_to_Address'=> $Ship_to_Address,
							'Ship_to_Address_2'=> $Ship_to_Address_2,
							'Ship_to_Post_Code'=> $Ship_to_Post_Code,
							'Ship_to_City'=> $Ship_to_City,
							'Ship_to_Contact'=> $Ship_to_Contact,
							'Inbound_Whse_Handling_Time'=> $Inbound_Whse_Handling_Time,
							'Shipment_Method_Code'=> $Shipment_Method_Code,
							'Lead_Time_Calculation'=> $Lead_Time_Calculation,
							'Requested_Receipt_Date'=> $Requested_Receipt_Date,
							'Promised_Receipt_Date'=> $Promised_Receipt_Date,
							'Sell_to_Customer_No'=> $Sell_to_Customer_No,
							'Ship_to_Code'=> $Ship_to_Code,
							'Modified_Time'=> $Modified_Time,
							'Modified_Date'=> $Modified_Date,
							'Transaction_Type'=> $Transaction_Type,
							'Transaction_Specification'=> $Transaction_Specification,
							'Transport_Method'=> $Transport_Method,
							'Entry_Point'=> $Entry_Point,
							'Area'=> $Area,
							'Prepayment_Percent'=> $Prepayment_Percent,
							'Compress_Prepayment'=> $Compress_Prepayment,
							'Prepmt_Payment_Terms_Code'=> $Prepmt_Payment_Terms_Code,
							'Prepayment_Due_Date'=> $Prepayment_Due_Date,
							'Prepmt_Payment_Discount_Percent'=> $Prepmt_Payment_Discount_Percent,
							'Prepmt_Pmt_Discount_Date'=> $Prepmt_Pmt_Discount_Date,
							'Vendor_Cr_Memo_No'=> $Vendor_Cr_Memo_No
							*/
						));
				$result2 = $service->Update($params2); 

			}
		}

			//echo json_encode($result2);
			//$resultSet = $result->ReadMultiple_Result->PurchOrderNav;
			/*
			foreach ($resultSet as $b)
			{	
				echo $b->No;
				echo ' ';
				echo $b->Order_Date;
				echo ' ';
				echo $b->Location_Code;				
				echo ' ';
				echo $b->Buy_from_Vendor_No;
				echo ' ';
				echo $b->Due_Date;
				echo '<br>';
				$hasil = $b->PurchLines->Purchase_Order_Line;
				if (is_array($hasil)) {
					foreach ($hasil as $item)
					{
						echo '&nbsp&nbsp { ';
						echo 'Item Code : '.$item->No;
						echo ' | ';
						echo 'Quantity : '.$item->Quantity;
						echo ' | ';
						echo 'UoM : '.$item->Unit_of_Measure_Code;
						echo ' } ';			
						echo '<br>';
					}
				}
				else {
					echo '&nbsp&nbsp { ';
					echo 'Item Code : '.$hasil->No;
					echo ' | ';
					echo 'Quantity : '.$hasil->Quantity;
					echo ' | ';
					echo 'UoM : '.$hasil->Unit_of_Measure_Code;
					echo ' } ';		
					echo '<br>';
				}
				
			};*/
			/*
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

