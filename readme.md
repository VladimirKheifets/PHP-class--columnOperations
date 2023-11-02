# PHP class сolumnOperations

### Version: 1.0, 2023-11-02

Author: Vladimir Kheifets <vladimir.kheifets.@online.de>

Copyright &copy; 2023 Vladimir Kheifets All Rights Reserved

The class provides multiplication and addition of wholes number across columns, as well as outputting each digit of a number in HTML div tags.
 
Demo
[https://www.alto-booking.com/developer/columnOperations/](https://www.alto-booking.com/developer/columnOperations/)



### 1. File  сolumnOperationsClass.php

```php
<?
/*
    PHP class сolumnOperations
    Version: 1.0, 2023-11-02
    Author: Vladimir Kheifets (vladimir.kheifets.@online.de)
    Copyright (c) 2023 Vladimir Kheifets All Rights Reserved
    Demo:
    https://www.alto-booking.com/developer/columnOperations
*/

class сolumnOperations {
    private $numArr;
    private $maxColumn;

    public function сolumnMultiplication($a, $b){

        /*
        Parameters (intenger):
        $a - multiplier
        $b - multiplicand
        Return object whith properties:
        strArr - (string) an array of intermediate results of multiplication
        product - (integer) multiplication result
        */

        $aArr = array_reverse(str_split($a));
        $bArr = array_reverse(str_split($b));
        foreach($bArr as $i => $numB){
            $d=0;
            $buf="";
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
        return (object)
        [
           "strArr" => $this -> strArr,
           "product" => $this -> сolumnAddition()
        ];
    }

    public function сolumnAddition($numArr = null){

        /*
        Parameter:
        $numArr - (integer) an array of summands
        or null, in case this method is called in the сolumnMultiplication method
        Return: (integer) $sum
        */

        if(is_null($numArr)) $numArr = $this-> numArr;
        $k = strlen((string)max($numArr));
        foreach($numArr as $i=>$num)
            $buf[] = array_pad(array_reverse(str_split($num)), $k, 0);
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

    function numberToDivStr($num, $rowIndex = null ){

        /*
        Parameter:
        $num - (integer) the number that should be output in html
        $rowIndex - (integer) row index for displaying a number with a digit shift
        Must be defined only after calling the сolumnMultiplication method
        null - in other cases.
        Return: (string) HTML - line in which each digit of a number is displayed in div tags.
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
}
?>
```

### 2. Demo of the сolumnOperations class

### 2.1 File index.php

```php
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
?>
```
### 2.2 File index.css

```css
body{
    font-family: arial;
    font-size: 12px
}

table div{
    display: inline-block;
    border: 1px solid #cccccc;
    padding: 1px;
    margin:1px;
    min-width: 1em;
    text-align: center
}

td:first-of-type{
    text-align: center;
    min-width: 40px;
}

td:first-of-type + td{
    min-width: 200px;
    text-align: left;
}

.tit{
    font-size: 24px;
    padding: 0 0 20 0;
    text-align: center;
}

.col{
    float: left;
    width: 50%;
    text-align: center;
    padding-top: 50px
}

button{
    position: absolute;
    top:10px;
    left: 33%;
    z-index: 1;
    font-size: 16px;
    cursor: pointer
}
```