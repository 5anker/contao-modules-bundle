<?php

namespace Anker\ModulesBundle\Helper;

class Formatter
{
	public static function date($date)
	{
		return date('d.m.Y', strtotime($date));
	}

	public static function time($time)
	{
		return date('H:i', strtotime('01.01.2016 '.$time));
	}

	public static function percent($number)
	{
		return number_format($number, 2, ',', '.').' %';
	}

	public static function m($number)
	{
		return number_format($number, 2, ',', '.').' m';
	}

	public static function l($number)
	{
		return number_format($number, 0, ',', '.').' l';
	}
}
