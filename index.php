<!DOCTYPE html>
<html>

<head><title>Tips Calculator</title>
</head>
<style>
div.container {
    width: 30%;
    border: 1px solid gray;
}

header, footer {
    padding: 1em;
    color: black;
    background-color: #E6E6FA;
    clear: left;
    text-align: center;
}


</style>
</head>
<body>
<div class="container">

<header>
   <h1>Tips Calculator</h1>
</header>

<?php

  function process_input($data) 
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $subTotalErr = false;
  $percentageErr = false;
  $splitErr = false;
  $subTotal = "";
  $percentage = 20;
  $customPercentage = "";
  $splitPerson = 1;

  if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {
      if (empty($_POST["subTotal"])) 
      {
        $subTotalErr = true;
        $subTotal = "";
      } 
      else 
      {
        $subTotal = process_input($_POST["subTotal"]);
        if (!is_numeric($subTotal) || $subTotal <= 0) 
        {
          $subTotalErr = true; 
        }
      }
    
      if (empty($_POST["percentage"])) 
      {
        $percentageErr = true;
        $percentage = 20;
      } 
      else 
      {
        $percentage = $_POST["percentage"];
        if($percentage == "custom")
        {
          $customPercentage = process_input($_POST["custom"]);
          if (!is_numeric($customPercentage) || $customPercentage <= 0) 
          {
              $percentageErr = true; 
          }
        }
      }
      
      if (empty($_POST["splitPerson"])) 
      {
        $splitErr = true;
        $splitPerson = "";
      } 
      else 
      {
        $splitPerson = process_input($_POST["splitPerson"]);
        if (!ctype_digit($splitPerson)) 
        {
            $splitErr = true; 
        }
      }
  }

?>
  
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

  <p <?php if($subTotalErr){ echo 'style="color:red;"';} ?> >
  &nbsp; Bill subtotal: $<input type="text" name="subTotal" value="<?php echo $subTotal;?>" />
  </p>

  <p <?php if($percentageErr){ echo 'style="color:red;"';} ?> >

  &nbsp; Tip percentage:<br>
  <?php
  for ($i = 10; $i <= 20; $i += 5) {
      echo '<input type="radio" name="percentage" value="',$i,'"';
      if($percentage == $i) echo 'checked';
      echo '> ',$i,'%';
  }
  ?>
  <input type="radio" name="percentage" value="custom" <?php if($percentage == "custom") echo 'checked'; ?> >
  <br>
  &nbsp; Custom: <input type="text" name="custom" value="<?php echo $customPercentage;?>" />%.
  </p>

  <p <?php if($splitErr){ echo 'style="color:red;"';} ?> >
  &nbsp; Split: <input type="text" name="splitPerson" value="<?php echo $splitPerson;?>" /> person(s).
  </p>
      
  <p><input type="submit" /></p>
  </form>

  <?php
    $tip = 0;
    $total = 0;
    if($_SERVER["REQUEST_METHOD"] == "POST" && !$subTotalErr && !$percentageErr && !$splitErr)
    {
      if($percentage == "custom")
      {
        $tip = $subTotal * $customPercentage / 100;
      }
      else
      {
        $tip = $subTotal * $percentage / 100;
      }
      $total = $subTotal + $tip;
      $tip = number_format((float)$tip, 2, '.', '');
      $total = number_format((float)$total, 2, '.', '');
      echo "<font color='blue'>&nbsp; Tip: $tip</font>";
      echo '<br>';
      echo "<font color='blue'>&nbsp; Total: $total</font>";
      echo '<br>';
      if($splitPerson > 1)
      {
        $tip = number_format((float)$tip / $splitPerson, 2, '.', '');
        echo "<font color='blue'>&nbsp; Tip each: $tip</font>";
        echo '<br>';
        $total = number_format((float)$total / $splitPerson, 2, '.', '');
        echo "<font color='blue'>&nbsp; Total each: $total</font>";
        echo '<br>';
      }
    }
  ?>

</body>
</html>
<br>


<footer>Copyright 2017</footer>

</div>

</body>
</html>

