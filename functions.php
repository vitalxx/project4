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
  $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  
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
    case "January":
      return ($day <= 31);
    case "February":
      return ($day <= 28);
    case "March":
      return ($day <= 31);
    case "April":
      return ($day <= 30);
    case "May":
      return ($day <= 31);
    case "June":
      return ($day <= 30);
    case "July":
      return ($day <= 31);
    case "August":
      return ($day <= 31);
    case "Septempber":
      return ($day <= 30);
    case "October":
      return ($day <= 31);
    case "November":
      return ($day <= 30);
    case "December":
      return ($day <= 31);
    default:
      return "false";
  }
} //validDay()

function getCurrentMonth($mon)
{
  $mon  = date("F");
  $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  
  for($i = 0; $i < count($months); $i++)
  {
    if($mon == $months[i])
    {
      return $mon;
    }
    return $mon;
  }
}

//get $months array
function getAllMonths()
{
  $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  return $months;
}