<?
include_once("сolumnOperationsClass.php");
$сolOp = new сolumnOperations();

//Test column multiplication
$multiplier = rand(10, 100000);
$multiplicand = rand(10, 100000);
#######################################################
$res = $сolOp -> сolumnMultiplication($multiplier, $multiplicand);
$product = $res -> product;
$strArr = $res -> strArr;
$multiplierDiv = $сolOp -> numberToDivStr($multiplier);
$multiplicandDiv = $сolOp -> numberToDivStr($multiplicand);
$productDiv = $сolOp -> numberToDivStr($product);
$resStrAll = "";
foreach($strArr as $i => $number){
	$resStrAll .= $сolOp -> numberToDivStr($number, $i);
}
$productPHPMultiplication = $multiplier * $multiplicand;
echo <<<HTML
<html>
<head>
<title>Demo PHP class сolumnOperations</title>
<link rel="stylesheet" type="text/css" href="index.css" />
</head>
<body>
<div class="col">
<table align="center">
<tr>
<td colspan=2 class="tit">Column multiplication</td>
</tr>
<tr>
<td> &times; </td>
<td>$multiplierDiv $multiplicandDiv</td>
</tr>
<tr>
<td colspan=2 ><hr></td>
</tr>
<tr>
<td> + </td>
<td>$resStrAll</td>
</tr>
<tr>
<td colspan=2 ><hr></td>
</tr>
<tr>
<td> = </td>
<td>$productDiv</td>
</tr>
<tr>
<td colspan=2 ><hr></td>
</tr>
</table>
<table align="center">
<tr>
<td>
<br>
Result of multiplication by operator *: <p>$multiplier * $multiplicand = $productPHPMultiplication</p>
</td>
</tr>
</table>
</div>
HTML;
#######################################################
// Test Column addition
for ($i=0; $i < 6; $i++)
	$numArr[$i] = rand(1, pow(10, $i+1));
shuffle($numArr);
//-------------------------------------------
$sum = $сolOp -> сolumnAddition($numArr);
$sumDiv = $сolOp -> numberToDivStr($sum);

$operandsStr = "";
foreach($numArr as $i => $number){
	$operandsStr .= $сolOp -> numberToDivStr($number);
}
$sumFromArraySum = array_sum($numArr);
$numArrToStr  = implode(", ", $numArr);
echo <<<HTML
<div class="col">
<table>
<tr>
<td colspan=2 class="pt tit"><p>Column addition</p></td>
</tr>
<tr>
<td> + </td>
<td>$operandsStr</td>
</tr>
<tr>
<td colspan=2 ><hr></td>
</tr>
<tr>
<td> = </td>
<td>$sumDiv</td>
</tr>
<td colspan=2 ><hr></td>
</tr>
<tr>
<td colspan=2>
<br>
</td>
</tr>
</table>
<table>
<tr>
<td>
Result of addition by PHP function array_sum:
<br>&nbsp;<br>array_sum ($numArrToStr)
<br>= $sumFromArraySum
</td>
</tr>
</table>
</div>
<button onClick = "location.reload()">Generate other random operands</button>
HTML;
?>
</body>
</html>