<?
include_once("сolumnOperationsClass.php");
$сolOp = new сolumnOperations();
$title = "Columns operations of whole numbers";
$operations =
[
	"Addition of two numbers",
	"Addition of several numbers",
	"Multiplication of two numbers",
	"Subtraction of two numbers",
	"Division of two numbers"
];

$matheSymbols =
[
	"&plus;",
	"&times;",
	"&minus;",
	"&divide;"
];

$case = isset($_GET["case"])?$_GET["case"]:"0";
$ul = "<ul onChange = 'go()'>";
foreach($operations as $key => $val)
{
	$active = $key==$case?"class='active'":"onClick=go($key)";
	$ul .= "<li $active>$val</li>";
}
$ul .= "<li onClick = 'go($case)'>Generate other random operands</li>";
$ul .= "</ul>";

$trM = "";
switch ($case) {
	case 0:
		// Addition of two numberscode
		$matheSymbol = $matheSymbols[0];
		$augend =  rand(10, 100000);
		$addend = rand(10, 100000);
		$sum = $сolOp -> сolumnAddition($augend, $addend);
		$operands = $сolOp -> numberToDivStr($augend);
		$operands .= $сolOp -> numberToDivStr($addend);
		$result = $сolOp -> numberToDivStr($sum);
		$resultPHP = "of addition:";
		$sumPHP = $augend + $addend;
		$resultPHP .= "<br>$augend + $addend = $sumPHP";
		break;

	case 1:
		// Addition of several numbers
		$matheSymbol = $matheSymbols[0];
		for ($i=0; $i < 6; $i++)
			$numArr[$i] = rand(1, pow(10, $i+1));
		shuffle($numArr);
		//-------------------------------------------
		$sum = $сolOp -> сolumnAdditionArr($numArr);
		$result = $сolOp -> numberToDivStr($sum);

		$operands = "";
		foreach($numArr as $i => $number){
			$operands .= $сolOp -> numberToDivStr($number);
		}
		$sumFromArraySum = array_sum($numArr);
		$numArrToStr  = implode(" + ", $numArr);
		eval("\$sumPHP=$numArrToStr;");
		$resultPHP = "of addition:";
		$resultPHP .= "<br>$numArrToStr = $sumPHP";
		break;

	case 2:
		// Multiplication of two numbers
		$matheSymbol = $matheSymbols[1];
		$multiplier = rand(10, 100000);
		$multiplicand = rand(10, 100000);
		$product = $сolOp -> сolumnMultiplication($multiplier, $multiplicand);
		$strArr = $сolOp -> strArr;
		$operandsM = $сolOp -> numberToDivStr($multiplier);
		$operandsM .= $сolOp -> numberToDivStr($multiplicand);
		$result = $сolOp -> numberToDivStr($product);
		$operands = "";
		foreach($strArr as $i => $number)
			$operands .= $сolOp -> numberToDivStr($number, $i);
		$trM = <<<HTML
		<tr>
		<td> &times; </td>
		<td>$operandsM<hr></td>
		</tr>
		HTML;
		$matheSymbol = $matheSymbols[0];
		$resultPHP = "of multiplication:";
		$resultPHP .= "<br>$multiplier * $multiplicand = ".$multiplier * $multiplicand;
		break;

	case 3:
		// Subtraction of two numbers
		$matheSymbol = $matheSymbols[2];
		do{
			$minuend =  rand(10, 100000);
			$subtrahend = rand(10, 100000);
		} while ($subtrahend > $minuend);

		$differenz = $сolOp -> сolumnSubtraction($minuend, $subtrahend);
		$operands = $сolOp -> numberToDivStr($minuend);
		$operands .= $сolOp -> numberToDivStr($subtrahend);
		$result = $сolOp -> numberToDivStr($differenz);
		$resultPHP = "of subtraction:";
		$differenzPHP = $minuend - $subtrahend;
		$resultPHP .= "<br>$minuend - $subtrahend = $differenzPHP";
		break;

	case 4:
		// Division of two numbers
		do{
		   $dividend =  rand(1, 100000);
		   $divisor = rand(2, 100); //1000
		} while ($divisor > $dividend);

		$resultPHP = "of division:";
		$quotientPHP = $dividend / $divisor;
		$resultPHP .= "<br>$dividend / $divisor = $quotientPHP";
		$quotient = $сolOp -> сolumnDivision($dividend, $divisor, true);
		$divisionStepsToHTML =  $сolOp -> сolumnDivisionToHTML();
		$result = "";
		break;
}

echo <<<HTML
<html>
<head>
<title>Demo PHP class сolumnOperations</title>
<meta name="viewport" content="width=device-width,height=device-height,user-scalable=yes">
<link rel="stylesheet" type="text/css" href="index.css" />
<script>
function go(c){
	window.location.assign("?case="+c);
}
</script>
</head>
<body>
<table class="tabHeader" align="center" >
<tr>
<td class="tit">$title</td>
</tr>
<tr>
<td>$ul
<div>PHP result $resultPHP</div>
</td>
</tr>
</table>
HTML;

if($case == 4)
	echo $divisionStepsToHTML;
else
	echo <<<HTML
	<div align="center" class="tabCalculation">
	<table>
	$trM
	<tr>
	<td> $matheSymbol </td>
	<td>$operands<hr></td>
	</tr>
	<tr>
	<td> = </td>
	<td>$result</td>
	</tr>
	</table>
	</div>
	HTML;
?>
</body>
</html>