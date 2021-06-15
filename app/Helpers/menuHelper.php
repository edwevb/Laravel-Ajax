<?php

function getMenu()
{
	$menu = \DB::table('biddings')->select('opening')->get();
	return $menu;
}