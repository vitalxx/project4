<?php

/*
 * validMonthAndDay($mon, $day)
 * @params
 * $mon = string month
 * $day = int day
 * 
 * returns boolean
 */
function validMonthAndDay($mon, $day)
{
  $months = ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"];
  
  for($i = 0; $i < count($months); $i++)
  {
    echo $i . " " . validDay($mon, $day);
    if($months[$i] == $mon)
    {
      return validDay($mon, $day);
    }
    
    return false;
  }
}//validMonth()

/*
 * validDay($mon, $day)
 * @params
 * $mon = string month
 * $day = int day
 * 
 * returns boolean
 */
function validDay($mon, $day)
{
  switch($mon)
  {
    case "jan":
      return ($day <= 31);
    case "feb":
      return ($day <= 28);
    case "mar":
      return ($day <= 31);
    case "apr":
      return ($day <= 30);
    case "may":
      return ($day <= 31);
    case "jun":
      return ($day <= 30);
    case "jul":
      return ($day <= 31);
    case "aug":
      return ($day <= 31);
    case "sep":
      return ($day <= 30);
    case "oct":
      return ($day <= 31);
    case "nov":
      return ($day <= 30);
    case "dec":
      return ($day <= 31);
    default:
      return "false";
  }
} //validDay()