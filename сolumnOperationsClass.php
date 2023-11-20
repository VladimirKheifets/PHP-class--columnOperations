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

		Assigns a value to fields:
		public strArr (array)
		private numArr (array)

		Returns:
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
		return 	$this -> сolumnAdditionArr();
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
		$subtrahend	- (integer)

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
		return 	$divisionStepsToHTML;
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