# PHP class сolumnOperations

### Version: 2.0, 2023-11-20

Author: Vladimir Kheifets <vladimir.kheifets.@online.de>

Copyright &copy; 2023 Vladimir Kheifets All Rights Reserved

The class provides multiplication and addition of wholes number across columns, as well as outputting each digit of a number in HTML div tags.
 
Demo
[https://www.alto-booking.com/developer/columnOperations/](https://www.alto-booking.com/developer/columnOperations/)

### 1. Methods of the сolumnOperations class

### 1.1 Basic methods

#### 1.1.1 Method сolumnAddition

##### сolumnAddition($augend, $addend)
Parameters:<br>$augend - (integer)<br>$addend - (integer)

Assigns a value to private fields:<br>
maxColumn - (integer)

Returns:<br>(integer) $sum

#### 1.1.2 Method columnAdditionArr

##### columnAdditionArr($numArr)

Parameter:<br>$numArr - (integer) an array of summands or null, in case this method is called in the сolumnMultiplication method, gets the value from a private field: numArr

Assigns a value to private fields:<br>maxColumn - (integer)

Returns:<br>(integer) $sum


#### 1.1.3 Method сolumnMultiplication

##### сolumnMultiplication($multiplier, $multiplicand)

Parameters:<br>$multiplier - (integer)<br>$multiplicand - (integer)

Assigns a value to fields:<br>public strArr - (array)<br>private numArr - (array)

Returns:<br>product - (integer) multiplication result

#### 1.1.4 Method сolumnSubtraction

##### сolumnSubtraction($minuend, $subtrahend)

Parameters:<br>$minuend - (integer)<br>$subtrahend - (integer)

Assigns a value to private fields:<br>maxColumn - (integer)

Returns:<br>$difference - (integer)

#### 1.1.5 Method сolumnDivision

##### сolumnDivision($dividend, $divisor, $withoutRemainder = false )

Parameters:<br>$dividend - (integer)<br>$divisor - (integer)<br>
$withoutRemainder (boolean):<br>true - returns quotient (float) whith 15 digits<br>false - returns  quotient (float) without of precision

Assigns a value to public fields:<br>quotient - (float)<br>remainder - (integer)<br>divisionAllStepArr - an array of intermediate results for all steps of division

Returns:<br>$quotient - (float)

### 1.2 Methods for outputting the results of basic methods in HTML format

#### 1.2.1 Method numberToDivStr

##### numberToDivStr($number, $rowIndex = null )

Parameters:<br>$num - (integer) the number that should be output in html<br>$rowIndex - null or (integer)  row index for displaying a number with a digit shift<br>null - only after calling the сolumnMultiplication method, must be defined in other cases

Returns:<br>(string) HTML - line in which each digit of a number is displayed in div tags.
Complemented with empty (`&nbsp;`) div tags to the maximum number of digits in the sum or product


#### 1.2.2 Method сolumnDivisionToHTML

##### сolumnDivisionToHTML()

This Method gets the values of the class fields:<br>dividend,<br>divisor,<br>quotient,<br>remainder,<br>divisionAllStepArr

Returns the result of division as a string in HTML format

### 2 Quellcodes in PHP und CSS

#### 2.1 File  сolumnOperationsClass.php
```php
<?
/*
    PHP class сolumnOperations
    Version: 2.0, 2023-11-20
    Author: Vladimir Kheifets (vladimir.kheifets.@online.de)
    Copyright (c) 2023 Vladimir Kheifets All Rights Reserved
    Demo:
    https://www.alto-booking.com/developer/columnOperations
*/

class сolumnOperations {

    public $strArr;
    private $numArr;
    private $maxColumn;
    private $dividend;
    private $divisor;
    private $stepDivisionRightShift;
    private $quotient;
    private $remainder;
    private $divisionAllStepArr;

    public function сolumnMultiplication($a, $b){

        /*
        Parameters (intenger):
        $a  - (integer) multiplier
        $b  - (integer) multiplicand

        Returns object whith properties:
        strArr - (string) an array of intermediate results of multiplication
        product - (integer) multiplication result
        */

        $aArr = array_reverse(str_split($a));
        $bArr = array_reverse(str_split($b));
        foreach($bArr as $i => $numB){
            $d = 0;
            $buf = "";
            foreach($aArr as $j => $numA){
                $m = $numB * $numA + $d;
                $tu = $this->getTensUnits($m);
                $d = $tu[0];
                $m = $tu[1];
                $buf .= $m;
            }
            if( $d>0 ) $buf .= $d;
            $resStr = strrev($buf);
            $interimResult = intval($resStr);
            $this->numArr[] = $interimResult * pow ( 10, $i );
            $this->strArr[] = $resStr;
        }
        return  $this -> сolumnAdditionArr();
    }

    public function сolumnAddition($augend, $addend){

        /*
        Parameters:
        $augend - (integer)
        $addend - (integer)

        Assigns a value to private fields:
        maxColumn - (integer)

        Return:
        (integer) $sum
        */

        $k = strlen((string) max($augend, $addend));
        $augendArr = $this -> getDigitReverseArr($augend, $k);
        $addenddArr = $this -> getDigitReverseArr($addend, $k);

        $sum = "";
        $d = 0;
        foreach($augendArr as $i => $digAaugend){
            $sum1 = $digAaugend + $addenddArr[$i] + $d;
            if($sum1 > 9)
            {
                $d = 1;
                $sum1 -= 10;
            }
            else
                $d=0;
            $sum .= $sum1;
            $sum1 = 0;
        }
        $sum .= $d;
        $sum = (int)strrev($sum);
        $this -> maxColumn = strlen($sum);
        return $sum;
    }


    public function сolumnAdditionArr($numArr = null){

        /*
        Parameter:
        $numArr - (integer) an array of summands
        or null, in case this method is called in the сolumnMultiplication method,
        gets the value from a private field:
        numArr

        Assigns a value to private fields:
        maxColumn - (integer)

        Returns:
        (integer) $sum
        */

        if(is_null($numArr)) $numArr = $this-> numArr;
        $k = strlen((string) max($numArr));
        foreach($numArr as $i=>$num)
            $buf[] = $this -> getDigitReverseArr($num, $k);
        $sum = "";
        $d = 0;
        for ($i=0; $i < $k; $i++) {
            $sum1=0;
            foreach($buf as $j => $num)
            {
                $sum1 += $num[$i];
            }
            $sum1 += $d;
            $tu = $this -> getTensUnits($sum1);
            $d = $tu[0];
            $sum .= $tu[1];
        }
        $sum .= $d;
        $sum = (int)strrev($sum);
        $this -> maxColumn = strlen($sum);
        return $sum;
    }

    public function сolumnAdditionArr2($numArr = null){

        /*
        This method calls the сolumnAddition method to calculate the sum
        and it is an alternative to the сolumnAdditionArr method.

        Parameter:
        $numArr - (integer) an array of summands
        or null, in case this method is called in the сolumnMultiplication method,
        gets the value from a private field:
        numArr

        Assigns a value to private fields:
        maxColumn - (integer)

        Returns: (integer) $sum
        */

        if(is_null($numArr)) $numArr = $this-> numArr;
        $sum = 0;
        foreach($numArr as $i => $addend){
            $sum = $this -> сolumnAddition($sum, $addend);
        }
        $this -> maxColumn = strlen($sum);
        return $sum;
    }

    public function сolumnSubtraction($minuend, $subtrahend){

        /*
        Parameters:
        $minuend - (integer)
        $subtrahend - (integer)

        Assigns a value to private fields:
        maxColumn - (integer)

        Returns:
        $difference - (integer)
        */

        if($subtrahend > $minuend) return;
        $k = strlen((string) max($minuend, $subtrahend));
        $minuendArr = $this -> getDigitReverseArr($minuend, $k);
        $subtrahendArr = $this -> getDigitReverseArr($subtrahend, $k);
        $difference = "";
        $d = 0;
        foreach ($subtrahendArr as $i => $digS) {
            $digM = $minuendArr[$i] - $d;

            if($digS > $digM)
            {
                $dDig = $digM - $digS + 10;
                $d = 1;
            }
            else
            {
                $dDig = $digM - $digS;
                $d = 0;
            }
            $difference .= $dDig;
        }
        $this -> maxColumn = max(strlen($difference), $k);
        return (int)strrev($difference);
    }

    public function сolumnDivision($dividend, $divisor, $withoutRemainder = false ){

        /*
        Parameters:
        $dividend - (integer)
        $divisor - (integer)
        $withoutRemainder (boolean):
        true - returns quotient (float) whith 15 digits
        false - returns  quotient (float) without of precision

        Assigns a value to public fields:
        quotient - (float)
        remainder - (integer)
        divisionAllStepArr - an array of intermediate results for all steps of division

        Returns:
        $quotient - (float)
        */

        $divisionAllStepArr = [];
        $this -> dividend = $dividend;
        $this -> divisor = $divisor;
        $this -> stepDivisionRightShift = 0;
        if($divisor == 0) return "Error: Division by zero";
        $quotient = "";
        $i=0;
        $divisionAllStepArr = [];
        $lenDividend = strlen($dividend);
        $lenDivisor = strlen($divisor);
        $stepRemainder = 1;
        $stepSubtrahend=1;
        while($i<$lenDividend){
            if($i == 0)
            {
                $lenStepDividend = $divisor <= (int)substr($dividend,$i, $lenDivisor)?$lenDivisor:$lenDivisor + 1;
                $StepDividend = substr($dividend, $i, $lenStepDividend);
                $i += $lenStepDividend;
            }
            else
            {
                $StepDividend = $stepRemainder.substr($dividend, $i, 1);
                if($StepDividend < $divisor)
                {

                    $quotient .= "0";
                    $StepDividend = $stepRemainder.substr($dividend, $i, 2);
                    $i += 2;
                }
                else
                    $i += 1;
            }
            $divisionStepArr = $this -> longDivisionStep($StepDividend);
            $divisionAllStepArr[] = $divisionStepArr;
            extract($divisionStepArr);
            if($stepSubtrahend>0)
            $quotient .= $stepQuotient;
        }

        if($withoutRemainder)
        {
            if($stepRemainder>0)
            {
                $precision = 15 - strlen($quotient);
                $quotient .=".";
                $digits = 0;
                while($digits < $precision AND $stepRemainder>0){

                    $StepDividend = $stepRemainder."0";
                    if($StepDividend < $divisor )
                    {
                        $quotient .= "0";
                        $StepDividend = $stepRemainder."00";
                    }

                  $divisionStepArr = $this -> longDivisionStep($StepDividend);
                  $divisionAllStepArr[] = $divisionStepArr;
                  extract($divisionStepArr);
                  $quotient .= $stepQuotient;
                  $digits++;
               }
                $quotient = round($quotient, $precision - 1);
            }
        }
        $quotient = (float) $quotient;
        $stepRemainder = (int) $stepRemainder;
        $this -> quotient = $quotient;
        $this -> remainder = $stepRemainder;
        $this -> divisionAllStepArr = $divisionAllStepArr;
        return $quotient;
    }

    public function numberToDivStr($num, $rowIndex = null ){

        /*
        Parameters:
        $num - (integer) the number that should be output in html
        $rowIndex - null or (integer)  row index for displaying a number with a digit shift
        null - only after calling the сolumnMultiplication method, must be defined in other cases

        Returns: (string) HTML - line in which each digit of a number is displayed in div tags.
        Complemented with empty (&nbsp;) div tags to the maximum number of digits in the sum or product
        */

        if($num == 0)
        {
            $numArr[0] = 0;
            $numLen = 1;
        }
        else
        {
            $numArr = str_split($num);
            $numLen = strlen($num);
        }
        $maxColumn = $this -> maxColumn;
        $divStr = "";
        if($maxColumn)
        {
            $shiftLeft = $maxColumn - $numLen - 1 * $rowIndex;
            for ($i=0; $i < $shiftLeft; $i++) {
                $divStr .= "<div>&nbsp;</div>";
            }
        }
        foreach($numArr as $num)
        {
            $divStr .= "<div>$num</div>";
        }
        if($maxColumn)
        {
            $shiftRight = $maxColumn - $numLen - $shiftLeft;
            for ($i=0; $i < $shiftRight; $i++) {
                $divStr .= "<div>&nbsp;</div>";
            }
        }

        $divStr .= "<br>";
        return $divStr;
    }

    public function getTensUnits($n){

        /*
        Parameter:
        $n - (integer) the number 0 - 99
        Return: (integer) an array in which the 0-element contains tens,
        and the 1-element contains units of a given number.
        */

        return $n>9?str_split($n):[0, $n];
    }

    public function getDigitReverseArr($num, $maxDig){
        /*
        Parameter:
        $num - (integer) a number that must be converted into an array digit by digit
        $maxDig - (integer) maximum number of elements in the created array
        Return:
        An array containing the digits of the number in reverse order, padded with 0 values
        */
        return array_pad(array_reverse(str_split($num)), $maxDig, 0);
    }

    private function longDivisionStep($dividend){

        /*
            Parameter:
            $dividend - (integer)

            Assigns a value to private fields:
            stepDivisionRightShift

            Returnts an array with keys:
              "stepDividend",
              "stepSubtrahend",
              "stepQuotient",
              "stepRemainder",
              "stepDivisionRightShift"
        */

       $divisor = $this -> divisor;
       $remainder = $dividend;
       $quotient = 0;
       while ($remainder >= $divisor)
       {
          $remainder -= $divisor;
          $quotient++;
       }
        $stepSubtrahend = $dividend - $remainder;

        $stepDivisionRightShift = $thisStepRightShift = $this -> stepDivisionRightShift;
        if(preg_match("/^0/", $dividend))
        {
            $dividend = preg_replace("/^0/", "", $dividend);
            $thisStepRightShift++;
            $stepDivisionRightShift = $thisStepRightShift+1;
        }
        else
        {
            $stepDivisionRightShift = $stepDivisionRightShift + strlen($dividend) - strlen($remainder);
        }

        $this -> stepDivisionRightShift = $stepDivisionRightShift;

       return
       [
          "stepDividend" => $dividend,
          "stepSubtrahend" => $stepSubtrahend,
          "stepQuotient" => $quotient,
          "stepRemainder" => $remainder,
          "stepDivisionRightShift" => $thisStepRightShift
       ];
    }

    public function сolumnDivisionToHTML(){

        /*
        This Method gets the values of the class fields:
        dividend, divisor, quotient, remainder, divisionAllStepArr

        Returns the result of division as a string in HTML format
        */

        $dividend = $this -> dividend;
        $divisor = $this -> divisor;
        $quotient = $this -> quotient;
        $remainder = $this -> remainder;
        $divisionAllStepArr = $this -> divisionAllStepArr;

        $divisionStepsToHTML = <<<HTML
        <div class="divisionSteps" align="center">
        <table border=1>
        HTML;
        $iEnd = count($divisionAllStepArr) - 1;
        $stepDivisionRightShift=0;
        foreach($divisionAllStepArr as $i => $stepArr)
        {
           extract($stepArr);
           if($i==0)
           {
                $R = $remainder > 0?"R $remainder":"";
                $stepDividend = $dividend;
                $stepDividend .= <<<HTML
                <div>
                <span>$divisor</span><br>
                <span>$quotient $R</span>
                </div>
                HTML;
           }

           $remainderOut = ($i == $iEnd)?$stepRemainder:null;
           $divisionStepsToHTML .=  $this -> stepDivisionToHTML(
            $stepDividend,
            $stepSubtrahend,
            $stepDivisionRightShift,
            $remainderOut
           );
        }
        $divisionStepsToHTML .=  "</table></div>";
        return  $divisionStepsToHTML;
    }

    private function stepDivisionToHTML($stepDividend, $stepSubtrahend, $stepDivisionRightShift, $remainder=null){

        /*
            Parameters:
            $stepDividend, $stepSubtrahend, $stepDivisionRightShift - (integer)
            $remainder - null at all steps except the last step,
                         or (integer) at the last step
            This Method gets the values of the class fields:
            divisor

            Returns a string containing a right-shifted HTML table for each step
        */

        $divisor = $this -> divisor;

        if($stepDivisionRightShift)
        {
            $ml = $stepDivisionRightShift + 0.5;
            $stMl = "style='margin-left: {$ml}em'";
        }
        else
            $stMl= "";

        $remainderToHtml = "";
        if(isset($remainder))
        {
            $remainder = $this -> numPadLeft($stepDividend, $remainder);
            $remainderToHtml = <<<HTML
            <tr>
            <td rowspan="2" align="right">&nbsp;</td>
            <td align="left">$remainder</td>
            </tr>
            HTML;
        }
        if(is_numeric($stepDividend))
        {
            $wI = strlen($stepDividend) - 1;
            $stI = " style='min-width:{$wI}em;'";
            $stepSubtrahend = $this -> numPadLeft($stepDividend, $stepSubtrahend);
        }
        else
            $stI = "";

        return <<<HTML
        <table border="0" $stMl>
        <tr>
        <td rowspan="2" align="right" width="30"> &minus; </td>
        <td>$stepDividend</td>
        </tr>
        <tr>
        <td width="200" align = left><i $stI>$stepSubtrahend</i></td>
        </tr>
        $remainderToHtml
        </table>
        HTML;
    }

    private function numPadLeft($num1, $num2){

        /*
        $num1, $num2 - (integer)
        If the number of digits in $num1 is greater than in $num2

        Returns $num2 (string) padded with spaces of length $num1,
        otherwise returns $num2 unchanged.
        */

        $lenNum1 = strlen($num1);
        $lenNum2 = strlen($num2);
        if($lenNum2 < $lenNum1)
            $num2 = str_replace(" ","&nbsp;", sprintf("%' ".$lenNum1."d",$num2));
        return $num2;
    }
}
?>
```
### 2.2 File index.php
```php
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
```
### 2.3 File index.css
```css
body{
    font-family: arial;
}

.tabHeader
{
    font-size: 20px;
    text-align: center;
}

.tabHeader div
{
    border: 1px solid #000;
    padding: 10 20 10 20;
    margin-bottom: 20px;
    background-color: #ccc;
    border-radius: 5px;
    box-shadow: 5px 5px 10px silver;
}

.tit{
    font-size: 24px;
    text-align: center;
}

ul{
    margin-top: 10px;
    margin-bottom: 10px;
}

ul li{
    text-align: left;
    cursor: pointer;
    padding: 5px;
    text-decoration: none
}

.active{
    text-decoration: underline;
    cursor: none;
}

/*----------------------------------------*/

.tabCalculation div{
    font-family: Courier New, monospace;
   font-size: 20px;
    display: inline-block;
    border: 1px solid #cccccc;
    padding: 1px;
    margin:1px;
    min-width: 1em;
    text-align: center
}

.tabCalculation{
    padding:0 40 0 0;
}

.tabCalculation td[colspan="2"]{
text-align: center;
}

.tabCalculation td:first-of-type{
    text-align: right;
    padding: 0 20 0 0;
    font-size: 20px;
}

.tabCalculation td:first-of-type + td{
    display: inline-block;
    text-align: right;
}

/*----------------------------------------*/

.divisionSteps{
    padding-right: 200px;
}

.divisionSteps table{
   font-family: Courier New, monospace;
   font-size: 20px;
}
.divisionSteps table tr:first-of-type + tr td i{
    display: inline-block;
    border-bottom: 1px solid #000;
    font-style:normal;
}

.divisionSteps table div{
   display: inline-block;
   margin-left: 40px;
   position: absolute;
   border-left: 1px solid #000;
}
.divisionSteps table span{
    padding-left: 10px;
    display: inline-block;
}

.divisionSteps table  br +  span{
    border-top: 1px solid #000;
    padding-top: 2px;
}
```
