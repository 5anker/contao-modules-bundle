<?php

namespace Anker\ModulesBundle\Helper;

class Currency
{
	public static function parse($number, $sign = '€')
	{
		return number_format($number, 2, ',', '.').' '.$sign;
	}

	public static function vat($number, $percent = 19)
	{
		return $number / (100 + $percent) * $percent;
	}

	public static function parseToVat($number, $percent = 19, $sign = '€')
	{
		return static::parse(static::vat($number, $percent), $sign);
	}

	public static function mwst($number, $percent = 19)
	{
		return static::parse($number / (100 + $percent) * $percent);
	}

	public static function netto($number, $percent = 19)
	{
		return $number / (100 + $percent) * 100;
	}

	public static function parseToNetto($number, $percent = 19, $sign = '€')
	{
		return static::parse(static::netto($number, $percent), $sign);
	}

	public static function discount($number)
	{
		if ($number > 0) {
			return '- '.number_format($number, 2, ',', '.').' €';
		} else {
			return '+ '.number_format(-$number, 2, ',', '.').' €';
		}
	}

	public static function discountInvert($number)
	{
		if ($number > 0) {
			return '+ '.number_format($number, 2, ',', '.').' €';
		} else {
			return '- '.number_format(-$number, 2, ',', '.').' €';
		}
	}

	public static function sign($number)
	{
		if ($number > 0) {
			return '+ '.number_format($number, 2, ',', '.').' €';
		} else {
			return '- '.number_format(-$number, 2, ',', '.').' €';
		}
	}

	public static function signInvert($number)
	{
		if ($number > 0) {
			return '- '.number_format($number, 2, ',', '.').' €';
		} else {
			return '+ '.number_format(-$number, 2, ',', '.').' €';
		}
	}
}
